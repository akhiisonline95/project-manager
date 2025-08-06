<?php

class UserController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->model("User");
        $this->userModel = new User();
        $this->data = [
            "user" => Auth::user()
        ];
    }

    public function index()
    {
        Auth::requireRole('admin');
        $this->data["users"] = $this->userModel->getAll();
        $this->view('users/index');
    }

    protected function store($id = 0)
    {
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'username' => trim($_POST['username']),
                'password' => $_POST['password'],
                'role' => $_POST['role']
            ];
            if (empty($data["username"])) {
                $error = "Username is required.";
            }
            if ($id == 0 && empty($data["password"])) {
                $error = "Password is required.";
            }
            if (empty($data["role"])) {
                $error = "Role is required.";
            }

            if (empty($error)) {
                if ($id > 0) $this->userModel->update($id, $data);
                else  $this->userModel->create($data);
                $this->redirect("user");
            }
        }

        $this->data['userData'] = $id > 0 ? $this->userModel->findById($id) :null;
        $this->data['error'] = $error;
        $this->view('users/form');
    }

    public function create()
    {
        Auth::requireRole('admin');
        $this->store();
    }

    public function edit()
    {
        Auth::requireRole('admin');
        $id = $_GET['id'] ?? 0;
        if ($id > 0) {
            $this->store($id);
        } else {
            $this->redirect("user");
        }
    }

    public function delete()
    {
        Auth::requireRole('admin');
        $id = $_GET['id'] ?? null;
        if ($id) {
            $this->userModel->delete($id);
        }
        $this->redirect("user");
    }


}
