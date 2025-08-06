<?php

require_once __DIR__ . '/../../system/core/Controller.php';
require_once __DIR__ . '/../../system/libraries/Auth.php';
require_once __DIR__ . '/../models/Project.php';
require_once __DIR__ . '/../models/Task.php';

class DashboardController extends Controller
{
    public function __construct()
    {
        if (!Auth::isLoggedIn()) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }
    }

    public function index()
    {
        $user = Auth::user();
        $projectModel = new Project();
        $taskModel = new Task();

        $data = [];

        if ($user['role'] === 'admin') {
            // Admin: Show overall stats
            $data['projectCount'] = $projectModel->countAll();
            $data['taskCount'] = $taskModel->countAll();
            $data['userWorkloads'] = $taskModel->getUserWorkloads();  // e.g., task count per user
        } else {
            // Team member: Show assigned tasks only
            $data['assignedTasks'] = $taskModel->getTasksByAssignedUser($user['id']);
        }

        $data['user'] = $user;
        $this->view('dashboard/index',$data);
    }


}
