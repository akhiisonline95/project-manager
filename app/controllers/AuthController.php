<?php

require_once __DIR__ . '/../../system/core/Controller.php';
require_once __DIR__ . '/../models/User.php';

class AuthController extends Controller
{
    public function index(){
        $this->login();
    }
    public function login()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $username = trim($_POST['username'] ?? '');
                $password = trim($_POST['password'] ?? '');

                if (empty($username) || empty($password)) {
                    $error = 'Username and password are required.';
                } else {
                    $userModel = new User();
                    $user = $userModel->findByUsername($username);
                    if (!$user || !password_verify($password, $user['password'])) {
                        $error = 'Invalid username or password.';
                    } else {
                        $_SESSION['user'] = $user;
                        header('Location: index.php?controller=dashboard&action=index');
                        exit;
                    }
                }
            }

            $this->view('auth/login', ['error' => $error ?? null]);
        } catch (Exception $e) {
            var_dump($e->getMessage());
        }
    }


    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: index.php');
    }
}
