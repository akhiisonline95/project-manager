<?php

class Auth
{
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    public static function user()
    {
        return $_SESSION['user'] ?? null;
    }

    public static function requireRole($role)
    {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== $role)
            header('Location: index.php?controller=auth&action=login');
    }
}
