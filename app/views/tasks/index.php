<?php
$pageTitle = 'Projects';
$user = $user ?? ['username' => 'Guest', 'role' => 'member'];
$this->view("layouts/header");
?>


<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Tasks</h1>
        <a href="index.php?controller=task&action=create" class="btn btn-primary">Add Task</a>
    </div>

    <?php if (empty($records)): ?>
        <div class="alert alert-info">No tasks found.</div>
    <?php else: ?>
    <div class="table-responsive">
        <table class="table table-striped table-hover table-bordered align-middle">
            <thead class="table-primary">
            <tr>
                <th>ID</th>
                <th>Project</th>
                <th>Title</th>
                <th>Assigned To</th>
                <th>Due Date</th>
                <th>Priority</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($records as $task): ?>
                <tr>
                    <td><?= htmlspecialchars($task['id']) ?></td>
                    <td><?= htmlspecialchars($task['project_title'] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($task['title']) ?></td>
                    <td><?= htmlspecialchars($task['assigned_username'] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($task['due_date']) ?></td>
                    <td><?= ucfirst(htmlspecialchars($task['priority'])) ?></td>
                    <td><?= ucfirst(str_replace('_', ' ', htmlspecialchars($task['status']))) ?></td>
                    <td>
                        <a href="index.php?controller=task&action=edit&id=<?= $task['id'] ?>"
                           class="btn btn-sm btn-warning me-1" title="Edit">Edit</a>
                        <a href="index.php?controller=task&action=delete&id=<?= $task['id'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure you want to delete this task?');"
                           title="Delete">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php $this->view("layouts/pagination"); ?>
        <?php endif; ?>
    </div>
    <?php $this->view("layouts/footer"); ?>
