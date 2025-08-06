<?php
$pageTitle = 'Projects';
$user = $user ?? ['username' => 'Guest', 'role' => 'member'];
$this->view("layouts/header");
$totalPages = $totalPages ?? 1;
?>


<div class="main-content">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Projects</h1>
        <a href="index.php?controller=project&action=create" class="btn btn-primary">Add Project</a>
    </div>

    <?php if (empty($projects)): ?>
        <div class="alert alert-info">No projects found.</div>
    <?php else: ?>
        <table class="table table-hover table-bordered align-middle">
            <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Description</th>
                <th>Created By</th>
                <th>Created At</th>
                <th class="text-center" style="width: 130px;">Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($projects as $project): ?>
                <tr>
                    <td><?= htmlspecialchars($project['id']) ?></td>
                    <td><?= htmlspecialchars($project['title']) ?></td>
                    <td><?= nl2br(htmlspecialchars($project['description'])) ?></td>
                    <td><?= htmlspecialchars($project['created_by']) ?></td>
                    <td><?= htmlspecialchars($project['created_at']) ?></td>
                    <td class="text-center">
                        <a  href="index.php?controller=project&action=edit&id=<?= $project['id'] ?>"
                           class="btn btn-sm btn-warning me-1" title="Edit">
                            Edit
                        </a>
                        <a  href="index.php?controller=project&action=delete&id=<?= $project['id'] ?>"                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Are you sure you want to delete this project?');"
                           title="Delete">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php $this->view("layouts/pagination", ["totalPages" => $totalPages]); ?>

    <?php endif; ?>
</div>
<?php $this->view("layouts/footer"); ?>
