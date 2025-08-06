<?php

class TaskController extends Controller
{

    private Task $taskModel;
    private Project $projectModel;
    private User $userModel;
    private string $uploadDir;


    public function __construct()
    {
        $this->uploadDir = __DIR__ ."/../../uploads/tasks/";
        if (!Auth::isLoggedIn()) $this->redirect("auth");
        $this->model("Task");
        $this->model("Project");
        $this->model("User");

        $this->taskModel = new Task();
        $this->projectModel = new Project();
        $this->userModel = new User();
        $this->data = [
            "user" => Auth::user()
        ];
    }

    public function index()
    {
        $filters = [
            'controller' => 'task',
            'action' => 'index',
            'limit' => $_GET['limit'] ?? 10,
            'offset' => $_GET['offset'] ?? 0,
            'status' => $_GET['status'] ?? null,
            'priority' => $_GET['priority'] ?? null,
            'project_id' => $_GET['project_id'] ?? null,
        ];

        $tasks = $this->taskModel->getAll($filters);
        $this->data['filters'] = $filters;
        $this->data['records'] = $tasks["records"];
        $this->data['count'] = $tasks["count"];
        $this->view('tasks/index');
    }

    /**
     * @throws \Random\RandomException
     */
    protected function store($id = 0)
    {
        Auth::requireRole('admin');
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'project_id' => isset($_POST['project_id']) ? (int)$_POST['project_id'] : 0,
                'assigned_to' => trim($_POST['assigned_to'] ?? ''),
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'due_date' => trim($_POST['due_date'] ?? ''),
                'priority' => trim($_POST['priority'] ?? ''),
                'status' => trim($_POST['status'] ?? '')
            ];

            if (empty($data['project_id'])) {
                $error .= "Project is required.";
            }
            if (empty($data['assigned_to'])) {
                $error .= "Assigned To is required.";
            }
            if (empty($data['title'])) {
                $error .= "Title is required.";
            }
            if (empty($data['due_date'])) {
                $error .= "Due Date is required.";
            }
            if (empty($data['priority'])) {
                $error .= "Priority is required.";
            }
            if (empty($data['status'])) {
                $error .= "Status is required.";
            }
            if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['due_date'])) {
                $error .= "Due Date must be in YYYY-MM-DD format.";
            }


            if (empty($error)) {
                if ($id > 0) {
                    $this->taskModel->update($id, $data);
                }
                else {
                    $data['created_by'] = $this->data['user']['id'];
                    $id = $this->taskModel->create($data);
                }

                if (!empty($_FILES['task_files']['name'][0])) {
                    $allowedExtensions = ['pdf', 'doc', 'docx', 'jpg', 'jpeg'];
                    $allowedMimeTypes = [
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'image/jpeg',
                    ];
                    $maxFileSize = 5 * 1024 * 1024; // 5 MB


                    if (!is_dir($this->uploadDir)) {
                        mkdir($this->uploadDir, 0755, true);
                    }

                    $files = $_FILES['task_files'];

                    for ($i = 0; $i < count($files['name']); $i++) {
                        $tmpPath = $files['tmp_name'][$i];
                        $originalName = basename($files['name'][$i]);
                        $fileSize = $files['size'][$i];
                        $fileError = $files['error'][$i];

                        if ($fileError !== UPLOAD_ERR_OK) {
                            $error .= "Error uploading file: $originalName. ";
                            continue;
                        }

                        // Validate file extension
                        $extension = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
                        if (!in_array($extension, $allowedExtensions)) {
                            $error .= "File type not allowed: $originalName. ";
                            continue;
                        }

                        // Validate MIME type securely with Fileinfo
                        $finfo = finfo_open(FILEINFO_MIME_TYPE);
                        $mimeType = finfo_file($finfo, $tmpPath);
                        finfo_close($finfo);

                        if (!in_array($mimeType, $allowedMimeTypes)) {
                            $error .= "Invalid file type detected: $originalName. ";
                            continue;
                        }

                        // Validate file size
                        if ($fileSize > $maxFileSize) {
                            $error .= "File too large (max 5MB): $originalName.";
                            continue;
                        }

                        $safeName = time() . '_' . bin2hex(random_bytes(5)) . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $originalName);
                        $destination = $this->uploadDir . $safeName;

                        if (move_uploaded_file($tmpPath, $destination)) {
                            $this->taskModel->updateTaskFiles($id,  $safeName, $originalName);
                        } else {
                            $error .= "Failed to move uploaded file: $originalName.";
                        }
                    }
                }
                if (empty($error)) {
                    $this->redirect("task");
                }
            }
        }

        $this->data['projects'] = $this->projectModel->getAll();
        $this->data['taskData'] = $taskData = $id > 0 ? $this->taskModel->findById($id) : null;
        $this->data['users'] = $this->data['taskData'] ? $this->projectModel->projectMembersById($taskData["project_id"]) : $this->userModel->getMembers();
        $this->data['error'] = $error;
        $this->view('tasks/form');
    }

    public function create()
    {
        $this->store();
    }

    public function edit()
    {
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $this->store($id);
        } else {
            $this->redirect("task");
        }

    }

    public function delete()
    {
        Auth::requireRole('admin');
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->taskModel->delete($id);
        }
        $this->redirect("task");
    }

    public function taskData()
    {
        header('Content-Type: application/json');

        $id = $_GET['id'] ?? null;
        if (!$id) {
            echo json_encode(['error' => 'Task ID is required']);
            http_response_code(400);
            exit;
        }
        $task = $this->taskModel->getTasksByID($id);
        if ($task) {
            $task["members"] = $this->projectModel->projectMembersById($task["project_id"]);
            $task["files"] = $this->taskModel->taskFilesByTaskId($id);
            echo json_encode($task);
        } else {
            echo json_encode(['error' => 'Task not found']);
            http_response_code(404);
        }
        exit;
    }

    public function taskUpdate()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? null;
            if (!$id) {
                $_SESSION['error'] = "Invalid task ID.";
                $this->redirect("dashboard");
            }
            $data = [
                'priority' => $_POST['priority'] ?? '',
                'status' => $_POST['status'] ?? '',
                'assigned_to' => $_POST['assigned_to'] ?? null,
            ];

            // Basic validation
            if (empty($data['priority']) || empty($data['status']) || empty($data['assigned_to'])) {
                $_SESSION['error'] = "Please fill all required fields.";
                $this->redirect("dashboard");
            }

            // Update task data using model
            $updated = $this->taskModel->updateTaskByID($id, $data);

            if ($updated) {
                $_SESSION['success'] = "Task updated successfully.";
            } else {
                $_SESSION['error'] = "Failed to update task.";
            }

            $this->redirect("dashboard");
        }
    }

    public function deleteFile()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fileId = isset($_POST['file_id']) ? (int)$_POST['file_id'] : 0;
            $taskId = isset($_POST['task_id']) ? (int)$_POST['task_id'] : 0;

            if ($fileId > 0) {
                // Fetch file info from DB for the file path
                $file = $this->taskModel->findFileById($fileId);

                if ($file) {
                    // Delete the physical file if exists
                    $absoluteFilePath = $this->uploadDir . $file['file_path']; // adjust path accordingly
                    if (file_exists($absoluteFilePath)) {
                        unlink($absoluteFilePath);
                    }
                    // Delete the DB record
                    $this->taskModel->deleteFileById($fileId);

                    $_SESSION['success'] = "File deleted successfully.";
                } else {
                    $_SESSION['error'] = "File not found.";
                }
            } else {
                $_SESSION['error'] = "Invalid file ID.";
            }

            // Redirect back to the task detail page after deletion
            header('Location: index.php?controller=task&action=edit&id=' . $taskId);
            exit;
        }
    }


}
