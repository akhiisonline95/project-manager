<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title><?= $pageTitle ?? 'Project Management System' ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <link href="assets/css/style.css" rel="stylesheet"/>

    <script src="assets/js/jquery-3.7.0.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/moment.min.js"></script>
</head>
<body>

<div class="topbar">
    <span>Project Management System</span>
    <span class="username ">
       <span class="ms-3"> Welcome <?= htmlspecialchars($user['username'] ?? 'Guest') ?> &nbsp;|&nbsp; <a
                   href="index.php?controller=auth&action=logout" class="text-white text-decoration-none ">Logout</a>
    </span>
</div>
<div class="sidebar">
    <?php $controller = strtolower($_GET['controller']); ?>

    <a href="index.php?controller=dashboard&action=index" class="<?= $controller == 'dashboard' ? "active" : ""; ?>">Dashboard</a>
    <?php
    if (($user['role'] ?? '') === 'admin'): ?>
        <a href="index.php?controller=project&action=index" class="<?= $controller == 'project' ? "active" : ""; ?>">Projects</a>
        <a href="index.php?controller=task&action=index" class="<?= $controller == 'task' ? "active" : ""; ?>">Tasks</a>
        <a href="index.php?controller=user&action=index" class="<?= $controller == 'user' ? "active" : ""; ?>">
            Users</a>
    <?php endif; ?>
</div>
