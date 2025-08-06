<?php
const DB_HOST = 'localhost';
const DB_NAME = 'project_manager';
const DB_USER = '';
const DB_PASS = '';

class DB
{
    private static $instance = null;

    public static function get()
    {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            self::$instance = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
        }
        return self::$instance;
    }
}