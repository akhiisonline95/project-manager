<?php

require_once __DIR__ . '/../../system/core/Model.php';

class Project extends Model
{

    public function countAll()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM projects');
        return (int)$stmt->fetchColumn();
    }

    // Fetches all projects
    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM projects ORDER BY created_at DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Find project by ID
    public function findById($id)
    {
        // Fetch the main project record
        $stmt = $this->db->prepare("SELECT * FROM projects WHERE id = ?");
        $stmt->execute([$id]);
        $project = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($project) {
            $stmt2 = $this->db->prepare("SELECT user_id FROM project_members WHERE project_id = ?");
            $stmt2->execute([$id]);
            $memberIds = $stmt2->fetchAll(PDO::FETCH_COLUMN); // returns numerically indexed array of user_ids
            $project['members'] = $memberIds; // add as array column
        } else {
            $project['members'] = [];
        }
        return $project;
    }


    // Create new project
    public function create($data)
    {
        $sql = "INSERT INTO projects (title, description, created_by) VALUES (:title, :description, :created_by)";
        $stmt = $this->db->prepare($sql);
        $success = $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':created_by' => $data['created_by']
        ]);
        if ($success) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }

    // Update project by ID
    public function update($id, $data)
    {
        $sql = "UPDATE projects SET title=:title, description=:description WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':id' => $id
        ]);
    }

    public function updateMembers($memberIds, $projectId,$delete =false)
    {
        if($delete) {
            $stmt = $this->db->prepare('DELETE FROM project_members WHERE project_id = ?');
            $stmt->execute([$projectId]);
        }

        $stmt =  $this->db->prepare('INSERT INTO project_members (project_id, user_id) VALUES (?, ?)');
        foreach ($memberIds as $uid) {
            $stmt->execute([$projectId, $uid]);
        }
    }

    // Delete project by ID
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM projects WHERE id = ?");
        return $stmt->execute([$id]);
    }


    //project Members ById
    public function projectMembersById($projectId)
    {
        $stmt = $this->db->prepare(
            "SELECT u.id, u.username FROM users u
         INNER JOIN project_members pm ON u.id = pm.user_id
         WHERE pm.project_id = ?");
        $stmt->execute([$projectId]);
        return$stmt->fetchAll(PDO::FETCH_ASSOC);
    }


}
