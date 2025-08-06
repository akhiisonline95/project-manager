<?php
require_once __DIR__ . '/../../app/config/db.php';
class Model
{
    protected $db;

    public function __construct()
    {
        $this->db = DB::get();
    }
}