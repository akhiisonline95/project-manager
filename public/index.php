<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once __DIR__ . '/../system/core/Router.php';
require_once __DIR__ . '/../system/core/Controller.php';
require_once __DIR__ . '/../system/libraries/Auth.php';
$router = new Router();
$router->dispatch();
