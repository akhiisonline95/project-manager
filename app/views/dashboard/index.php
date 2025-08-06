<?php
$pageTitle = 'Dashboard';
$this->view("layouts/header");
?>

<div class="main-content">
    <?php if ($user['role'] === 'admin'): ?>
        <h2 class="mb-4">Admin Dashboard</h2>
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Projects</h5>
                        <p class="display-4"><?= (int)($projectCount ?? 0) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <h5 class="card-title">Total Tasks</h5>
                        <p class="display-4"><?= (int)($taskCount ?? 0) ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title mb-3">User Workloads</h5>
                        <ul class="list-group list-group-flush">
                            <?php if (!empty($userWorkloads)): ?>
                                <?php foreach ($userWorkloads as $workload): ?>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        User ID <?= htmlspecialchars($workload['user_id']) ?>
                                        <span class="badge bg-primary rounded-pill"><?= (int)$workload['task_count'] ?></span>
                                    </li>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <li class="list-group-item">No workload data.</li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <h2 class="mb-4">My Assigned Tasks</h2>
        <?php if (!empty($assignedTasks)): ?>
            <div class="table-responsive">
                <table class="table table-striped table-hover align-middle">
                    <thead class="table-primary">
                    <tr>
                        <th>Project ID</th>
                        <th>Title</th>
                        <th>Due Date</th>
                        <th>Priority</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($assignedTasks as $task): ?>
                        <tr>
                            <td><?= htmlspecialchars($task['project_id']) ?></td>
                            <td><?= htmlspecialchars($task['title']) ?></td>
                            <td><?= htmlspecialchars($task['due_date']) ?></td>
                            <td><?= ucfirst(htmlspecialchars($task['priority'])) ?></td>
                            <td><?= ucfirst(str_replace('_', ' ', htmlspecialchars($task['status']))) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted">You have no tasks assigned.</p>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php $this->view("layouts/footer");?>
