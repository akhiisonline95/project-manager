<?php

class ProjectController extends Controller
{
    private Project $projectModel;
    private User $userModel;

    public function __construct()
    {
        $this->model("Project");
        $this->model("User");
        $this->projectModel = new Project();
        $this->userModel = new User();
        $this->data = [
            "user" => Auth::user()
        ];
    }

    public function index()
    {
        Auth::requireRole('admin');
        $this->data['projects'] = $this->projectModel->getAll();
        $this->view('projects/index');
    }

    public function store($id = 0)
    {
        Auth::requireRole('admin');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => trim($_POST['title'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
            ];

            $members = $_POST['members'] ?? [];

            if (empty($data["title"])) {
                $error = "Title is required.";
            }
            if (!(is_array($members) && sizeof($members) > 0)) {
                $error = "Select at least one member.";
            }


            if (empty($error)) {
                if ($id > 0) {
                    $this->projectModel->update($id, $data);
                    $this->projectModel->updateMembers($members,$id,true);
                }
                else {
                    $data['created_by'] = $this->data['user']['id'];
                    $id = $this->projectModel->create($data);
                    $this->projectModel->updateMembers($members,$id);
                }


                $this->redirect("project");
            }
        }
        $this->data['projectData'] = $id > 0 ? $this->projectModel->findById($id) : null;
        $this->data['error'] = $error ?? null;
        $this->data['users'] =$this->userModel->getMembers();

        $this->view('projects/form');
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
            $this->redirect("project");
        }
    }

    public function delete()
    {
        Auth::requireRole('admin');
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->projectModel->delete($id);
        }
        $this->redirect("project");
    }
}
