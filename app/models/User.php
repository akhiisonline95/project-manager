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

    public function getMembers()
    {
        $stmt = $this->db->query("SELECT * FROM users WHERE role = 'member' ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT id, username, role FROM users ORDER BY username ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT id, username, role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (username,  password, role) VALUES (:username,  :password, :role)");
        return $stmt->execute([
            ':username' => $data['username'],
            ':password' => password_hash($data['password'], PASSWORD_DEFAULT),
            ':role' => $data['role']
        ]);
    }

    public function update($id, $data): bool
    {
        $sql = "UPDATE users SET username = :username, role = :role";
        $params = [
            ':username' => $data['username'],
            ':role' => $data['role'],
            ':id' => $id
        ];

        if (!empty($data['password'])) {
            $sql .= ", password = :password";
            $params[':password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $sql .= " WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    public function delete($id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

}
