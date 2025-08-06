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
        $this->data['user'] = Auth::user();
    }

    public function index()
    {
        $projectModel = new Project();
        $taskModel = new Task();

        if ($this->data['user']['role'] === 'admin') {
            // Admin: Show overall stats
            $this->data['projectCount'] = $projectModel->countAll();
            $this->data['taskCount'] = $taskModel->countAll();
            $this->data['userWorkloads'] = $taskModel->getUserWorkloads();  // e.g., task count per user
        } else {
            // Team member: Show assigned tasks only
            $filters=  [
                'controller' => 'dashboard',
                'action' => 'index',
                'limit' => $_GET['limit'] ?? 10,
                'offset' => $_GET['offset'] ?? 0,
            ];

            $tasks = $taskModel->getTasksByAssignedUser($this->data['user']['id'],$filters);
            $this->data['filters'] = $filters;
            $this->data['assignedTasks'] = $tasks["records"];
            $this->data['count'] = $tasks["count"];
        }

        $this->view('dashboard/index');
    }


}
