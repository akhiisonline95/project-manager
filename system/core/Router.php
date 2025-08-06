<?php

class Router
{
    public function dispatch()
    {
        $controllerName = isset($_GET['controller']) ? ucfirst($_GET['controller']) . 'Controller' : 'AuthController';
        $action = isset($_GET['action']) ? $_GET['action'] : 'login';

        $controllerPath = __DIR__ . "/../../app/controllers/{$controllerName}.php";
        if (file_exists($controllerPath)) {
            require_once $controllerPath;
            $controller = new $controllerName();
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                echo "Action not found!";
            }
        } else {
            echo "Controller not found!";
        }
    }
}
