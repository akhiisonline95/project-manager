<?php
$pageTitle = 'Projects';
$user = $user ?? ['username' => 'Guest', 'role' => 'member'];
$this->view("layouts/header");
$projects = $projects ?? [];
$users = $users ?? [];
$taskData = $taskData ?? null;
$error = $error ?? null;
?>

<div class="main-content">
    <h1><?= $taskData ? 'Edit Task' : 'Add New Task' ?></h1>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="taskForm" method="post" novalidate class="row"
          action="<?= isset($projectData) ? 'index.php?controller=task&action=edit&id=' . $projectData['id'] : 'index.php?controller=task&action=create' ?>">
        <?php if ($taskData): ?>
            <input type="hidden" name="id" value="<?= $taskData['id'] ?>"/>
        <?php endif; ?>

        <div class="mb-3 col-md-4 col-12">
            <label for="title" class="form-label">Task Title *</label>
            <input type="text" name="title" id="title" class="form-control" required
                   value="<?= htmlspecialchars($taskData['title'] ?? '') ?>"/>
            <div id="titleError" class="error-msg">Task title must be between 3 and 100 characters.</div>
        </div>

        <div class="mb-3 col-md-4 col-12">
            <label for="project_id" class="form-label">Project *</label>
            <select name="project_id" id="project_id" class="form-select" required>
                <option value="">Select Project</option>
                <?php foreach ($projects as $p): ?>
                    <option value="<?= $p['id'] ?>" <?= ($taskData && $taskData['project_id'] == $p['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($p['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div id="projectError" class="error-msg">Please select a project.</div>
        </div>

        <div class="mb-3 col-md-4 col-12">
            <label for="assigned_to" class="form-label">Assign To *</label>
            <select name="assigned_to" id="assigned_to" class="form-select" required>
                <option value="">Select User</option>
                <?php foreach ($users as $u): ?>
                    <option value="<?= $u['id'] ?>" <?= ($taskData && $taskData['assigned_to'] == $u['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($u['username']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <div id="assignedError" class="error-msg">Please select a user.</div>
        </div>



        <div class="mb-3 col-md-4 col-12">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control"
                      rows="4"><?= htmlspecialchars($taskData['description'] ?? '') ?></textarea>
        </div>

        <div class="mb-3 col-md-4 col-12">
            <label for="due_date" class="form-label">Due Date *</label>
            <input type="date" name="due_date" id="due_date" class="form-control" required
                   value="<?= htmlspecialchars($taskData['due_date'] ?? '') ?>"/>
            <div id="dateError" class="error-msg">Please select a due date.</div>
        </div>

        <div class="mb-3 col-md-4 col-12">
            <label for="priority" class="form-label">Priority *</label>
            <select name="priority" id="priority" class="form-select" required>
                <?php
                $priorities = ['low' => 'Low', 'medium' => 'Medium', 'high' => 'High'];
                $currentPriority = $taskData['priority'] ?? '';
                ?>
                <option value="">Select Priority</option>
                <?php foreach ($priorities as $key => $label): ?>
                    <option value="<?= $key ?>" <?= ($currentPriority == $key) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <div id="priorityError" class="error-msg">Please select a priority.</div>
        </div>

        <div class="mb-3 col-md-4 col-12">
            <label for="status" class="form-label">Status *</label>
            <select name="status" id="status" class="form-select" required>
                <?php
                $statuses = ['todo' => 'To Do', 'in_progress' => 'In Progress', 'done' => 'Done'];
                $currentStatus = $taskData['status'] ?? '';
                ?>
                <option value="">Select Status</option>
                <?php foreach ($statuses as $key => $label): ?>
                    <option value="<?= $key ?>" <?= ($currentStatus == $key) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <div id="statusError" class="error-msg">Please select a status.</div>
        </div>

        <div class="d-flex gap-2 align-items-center  justify-content-end">
            <button type="submit" class="btn btn-primary"><?= $taskData ? 'Update Task' : 'Add Task' ?></button>
            <a href="index.php?controller=task&action=index" class="btn btn-secondary ms-2">Cancel</a>
        </div>

    </form>
</div>


<script>
    $(document).ready(function () {
        $('#taskForm').on('submit', function (e) {
            let valid = true;

            if ($('#project_id').val() === '') {
                $('#projectError').show();
                valid = false;
            } else {
                $('#projectError').hide();
            }

            if ($('#assigned_to').val() === '') {
                $('#assignedError').show();
                valid = false;
            } else {
                $('#assignedError').hide();
            }

            const title = $('#title').val().trim();
            if (title.length < 3 || title.length > 100) {
                $('#titleError').show();
                valid = false;
            } else {
                $('#titleError').hide();
            }

            if ($('#due_date').val() === '') {
                $('#dateError').show();
                valid = false;
            } else {
                $('#dateError').hide();
            }

            if ($('#priority').val() === '') {
                $('#priorityError').show();
                valid = false;
            } else {
                $('#priorityError').hide();
            }

            if ($('#status').val() === '') {
                $('#statusError').show();
                valid = false;
            } else {
                $('#statusError').hide();
            }

            if (!valid) e.preventDefault();
        });

        $('#project_id').on('change', function () {
            var projectId = $(this).val();
            if (!projectId) {
                $('#assigned_to').html('<option value="">Select User</option>');
                return;
            }
            $.ajax({
                url: 'index.php?controller=project&action=members', // You will implement this action.
                type: 'GET',
                data: {project_id: projectId},
                dataType: 'json',
                success: function (data) {
                    var options = '<option value="">Select User</option>';
                    $.each(data, function (i, user) {
                        options += '<option value="' + user.id + '">' + user.username + '</option>';
                    });
                    $('#assigned_to').html(options);
                }
            });
        });

    });
</script>
<?php $this->view("layouts/footer"); ?>


