<?php
require_once __DIR__ . '/../../system/core/Model.php';

class User extends Model
{
    public function findByUsername($username)
    {
        $stmt = $this->db->prepare('SELECT * FROM users WHERE username=?');
        $stmt->execute([$username]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


}
