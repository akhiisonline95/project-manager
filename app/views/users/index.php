<?php
$pageTitle = 'Projects';
$user = $user ?? ['username' => 'Guest', 'role' => 'member'];
$this->view("layouts/header");
$filters = $filters ?? [];
$offset = array_key_exists('offset', $filters) ? (int)$filters['offset'] : 0;
?>


<div class="main-content">
    <div class="d-flex justify-content-between align-items-center">
        <h1>User Management</h1>
        <a href="index.php?controller=user&action=create" class="btn btn-primary mb-3">Add User</a>
    </div>


    <?php if (empty($users)): ?>
        <p>No users found.</p>
    <?php else: ?>
        <table class="table table-striped table-bordered">
            <thead class="table-primary">
            <tr>
                <th>#</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $k=>$u): ?>
                <tr>
                    <td><?=$offset+$k+1?></td>
                    <td><?= htmlspecialchars($u['username']) ?></td>
                    <td><?= htmlspecialchars($u['role']) ?></td>
                    <td>
                        <a href="index.php?controller=user&action=edit&id=<?= $u['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="index.php?controller=user&action=delete&id=<?= $u['id'] ?>"
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure to delete this user?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
<?php $this->view("layouts/footer"); ?>
