<?php

require_once __DIR__ . '/../../system/core/Model.php';

class Task extends Model
{
    public function countAll()
    {
        $stmt = $this->db->query('SELECT COUNT(*) FROM tasks');
        return (int)$stmt->fetchColumn();
    }

    public function getUserWorkloads()
    {
        $sql = "
            SELECT 
                u.id AS user_id, 
                u.username, 
                COUNT(t.id) AS task_count
            FROM users u
            LEFT JOIN tasks t ON u.id = t.assigned_to
            WHERE u.role = 'member'
            GROUP BY u.id, u.username
        ";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        $sql = "INSERT INTO tasks (project_id, assigned_to, title, description, due_date, priority, status,created_by) 
                VALUES (:project_id, :assigned_to, :title, :description, :due_date, :priority, :status,:created_by)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':project_id' => $data['project_id'],
            ':assigned_to' => $data['assigned_to'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':due_date' => $data['due_date'],
            ':priority' => $data['priority'],
            ':status' => $data['status'],
            ':created_by' => $data['created_by']
        ]);
    }

    public function update($id, $data)
    {
        $sql = "UPDATE tasks SET project_id=:project_id, assigned_to=:assigned_to, title=:title, 
                description=:description, due_date=:due_date, priority=:priority, status=:status
                WHERE id=:id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':project_id' => $data['project_id'],
            ':assigned_to' => $data['assigned_to'],
            ':title' => $data['title'],
            ':description' => $data['description'],
            ':due_date' => $data['due_date'],
            ':priority' => $data['priority'],
            ':status' => $data['status'],
            ':id' => $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM tasks WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM tasks WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAll($filters = []): array
    {
        // Build base SQL with JOINs and WHERE clause filters (without limit)
        $sqlBase = "FROM tasks t
        INNER JOIN projects p ON t.project_id = p.id
        INNER JOIN users u ON t.assigned_to = u.id
        WHERE 1=1";

        $params = [];

        if (!empty($filters['status'])) {
            $sqlBase .= " AND t.status = :status";
            $params[':status'] = $filters['status'];
        }
        if (!empty($filters['priority'])) {
            $sqlBase .= " AND t.priority = :priority";
            $params[':priority'] = $filters['priority'];
        }
        if (!empty($filters['project_id'])) {
            $sqlBase .= " AND t.project_id = :project_id";
            $params[':project_id'] = $filters['project_id'];
        }
        if (!empty($filters['assigned_to'])) {
            $sqlBase .= " AND t.assigned_to = :assigned_to";
            $params[':assigned_to'] = $filters['assigned_to'];
        }

        $sqlCount = "SELECT COUNT(*) as total " . $sqlBase;
        $stmtCount = $this->db->prepare($sqlCount);
        $stmtCount->execute($params);
        $count = (int)$stmtCount->fetchColumn();

        $sqlData = "SELECT t.*, p.title AS project_title, u.username AS assigned_username " . $sqlBase . " ORDER BY t.due_date DESC LIMIT :limit OFFSET :offset";
        $stmtData = $this->db->prepare($sqlData);

        foreach ($params as $key => $value) {
            $stmtData->bindValue($key, $value);
        }

        $limit = array_key_exists('limit', $filters) ? (int)$filters['limit'] : 10;
        $offset = array_key_exists('offset', $filters) ? (int)$filters['offset'] : 0;
        $stmtData->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmtData->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmtData->execute();

        $rows = $stmtData->fetchAll(PDO::FETCH_ASSOC);

        return ['count' => $count, 'records' => $rows];

    }

    public function getTasksByAssignedUser($userId, $filters = []): array
    {
        $limit = array_key_exists('limit', $filters) ? (int)$filters['limit'] : 10;
        $offset = array_key_exists('offset', $filters) ? (int)$filters['offset'] : 0;

        $countSql = "
        SELECT COUNT(*) 
        FROM tasks 
        WHERE assigned_to = :user_id
    ";

        $countStmt = $this->db->prepare($countSql);
        $countStmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $countStmt->execute();
        $count = (int)$countStmt->fetchColumn();

        $sql = "
        SELECT 
            t.*, 
            u_assigned.username AS assigned_username, 
            u_created.username AS created_by_username,
            p.title AS project_title
        FROM tasks t
        LEFT JOIN projects p ON p.id = t.project_id
        LEFT JOIN users u_assigned ON t.assigned_to = u_assigned.id
        LEFT JOIN users u_created ON t.created_by = u_created.id
        WHERE t.assigned_to = :user_id
        ORDER BY t.due_date ASC
        LIMIT :limit OFFSET :offset
    ";

        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return ['count' => $count, 'records' => $rows];
    }



    public function getTasksByID($taskId): array
    {
        $sql = "
            SELECT t.*, u_assigned.username AS assigned_username, u_created.username AS created_by_username,p.title as project_title
            FROM tasks t
            LEFT JOIN projects p ON p.id = t.project_id
            LEFT JOIN users u_assigned ON t.assigned_to = u_assigned.id
            LEFT JOIN users u_created ON t.created_by = u_created.id
            WHERE t.id = :task_id
            ORDER BY t.due_date ASC
        ";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':task_id' => $taskId]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function updateTaskByID($id, array $data): bool
    {
        $fields = [];
        $params = [':id' => $id];

        if (isset($data['priority'])) {
            $fields[] = "priority = :priority";
            $params[':priority'] = $data['priority'];
        }

        if (isset($data['status'])) {
            $fields[] = "status = :status";
            $params[':status'] = $data['status'];
        }

        if (isset($data['assigned_to'])) {
            $fields[] = "assigned_to = :assigned_to";
            $params[':assigned_to'] = $data['assigned_to'];
        }

        if (empty($fields)) {
            return false; // Nothing to update
        }

        $sql = "UPDATE tasks SET " . implode(", ", $fields) . " WHERE id = :id LIMIT 1";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

}
