<?php
$pageTitle = 'Users';
$user = $user ?? ['username' => 'Guest', 'role' => 'member'];
$projectData = $projectData ?? null;
$error = $error ?? null;

$this->view("layouts/header");
$users =$users ??[]
?>

<div class="main-content">
    <h1><?= isset($projectData) ? 'Edit Project' : 'Create New Project' ?></h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="projectForm"  method="post" novalidate  class="row" action="<?= isset($projectData) ? 'index.php?controller=project&action=edit&id=' . $projectData['id'] : 'index.php?controller=project&action=create' ?>">
        <div class="mb-3 col-md-4 col-12">
            <label for="title" class="form-label">Project Title <span class="text-danger">*</span></label>
            <input type="text" id="title" name="title" class="form-control" required
                   value="<?= htmlspecialchars($projectData['title'] ?? $_POST['title'] ??'') ?>" />
            <div id="titleError" class="error-msg">Please enter the project title (3-100 characters).</div>
        </div>

        <div class="mb-3 col-md-4 col-12">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" class="form-control" rows="5"><?= htmlspecialchars($projectData['description'] ?? $_POST['description'] ?? '') ?></textarea>
        </div>

        <div class="mb-3 col-md-4 col-12">
            <label for="members" class="form-label">Project Members</label>
            <select id="members" name="members[]" class="form-select" multiple>
                <?php foreach ($users as $user): ?>
                    <option value="<?= $user['id'] ?>"
                            <?php
                            $s_options =  $projectData['members'] ?? $_POST['description'] ?? [];
                            if (is_array($s_options) && !empty($s_options) && in_array($user['id'], $s_options)) echo 'selected';
                            ?>>
                        <?= htmlspecialchars($user['username']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <small class="form-text text-muted">Hold Ctrl (Windows) or Command (Mac) to select multiple users.</small>
        </div>

        <div class="d-flex gap-2 align-items-center  justify-content-end">
            <button type="submit" class="btn btn-<?= isset($projectData) ? 'primary' : 'success' ?>">
                <?= isset($projectData) ? 'Update Project' : 'Create Project' ?>
            </button>
            <a href="index.php?controller=project&action=index" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
</div>


<script>
    $(document).ready(function () {
        $('#projectForm').on('submit', function(e) {
            let valid = true;
            const title = $('#title').val().trim();
            if (title.length < 3 || title.length > 100) {
                $('#titleError').show();
                valid = false;
            } else {
                $('#titleError').hide();
            }
            if (!valid) e.preventDefault();
        });

        $('#title').on('input', function() {
            const title = $(this).val().trim();
            if (title.length >= 3 && title.length <= 100) {
                $('#titleError').hide();
            }
        });

    });
</script>
<?php $this->view("layouts/footer"); ?>


