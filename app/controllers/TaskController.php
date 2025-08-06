<?php

class TaskController extends Controller
{

    private Task $taskModel;
    private Project $projectModel;
    private User $userModel;

    public function __construct()
    {
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
                $error = "Project is required.";
            } else if (empty($data['assigned_to'])) {
                $error = "Assigned To is required.";
            } else if (empty($data['title'])) {
                $error = "Title is required.";
            } else if (empty($data['due_date'])) {
                $error = "Due Date is required.";
            } else if (empty($data['priority'])) {
                $error = "Priority is required.";
            } else if (empty($data['status'])) {
                $error = "Status is required.";
            } else if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $data['due_date'])) {
                $error = "Due Date must be in YYYY-MM-DD format.";
            } else {
                if ($id > 0) $this->taskModel->update($id, $data);
                else $this->taskModel->create($data);
                $this->redirect("task");
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
            $this->data['error'] = $this->taskModel->findById($id);
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
        $task = $this->taskModel->findById($id);
        if ($task) {
            echo json_encode($task);
        } else {
            echo json_encode(['error' => 'Task not found']);
            http_response_code(404);
        }
        exit;
    }

    public function taskUpdate()
    {
        session_start();

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


}
