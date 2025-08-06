<?php
$pageTitle = 'Users';
$user = $user ?? ['username' => 'Guest', 'role' => 'member'];
$this->view("layouts/header");
$userData = $userData ?? null;
$error = $error ?? null;
?>

<div class="main-content">
    <h1><?= $userData ? 'Edit User' : 'Add New User' ?></h1>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="userForm" method="post" novalidate  class="row"
          action="<?= isset($userData) ? 'index.php?controller=user&action=edit&id=' . $userData['id'] : 'index.php?controller=user&action=create' ?>">
        <?php if ($userData): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($userData['id']) ?>"/>
        <?php endif; ?>

        <div class="mb-3 col-md-4 col-12">
            <label for="username" class="form-label">Username *</label>
            <input type="text" id="username" name="username" class="form-control" required
                   value="<?= htmlspecialchars($userData['username'] ?? '') ?>"/>
            <div id="usernameError" class="error-msg">Please enter a username (3-50 characters).</div>
        </div>

        <div class="mb-3 col-md-4 col-12">
            <label for="password"
                   class="form-label"><?= $userData ? 'Password (leave blank to keep current)' : 'Password *' ?></label>
            <input type="password" id="password" name="password"
                   class="form-control" <?= $userData ? '' : 'required' ?> />
            <div id="passwordError"
                 class="error-msg"><?= $userData ? 'Password must be at least 6 characters if changed.' : 'Please enter a password (min 6 characters).' ?></div>
        </div>

        <div class="mb-3 col-md-4 col-12">
            <label for="role" class="form-label">Role *</label>
            <select id="role" name="role" class="form-select" required>
                <?php
                $roles = ['admin' => 'Admin', 'member' => 'Member'];
                $currentRole = $userData['role'] ?? '';
                ?>
                <option value="">Select Role</option>
                <?php foreach ($roles as $key => $label): ?>
                    <option value="<?= $key ?>" <?= ($currentRole == $key) ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <div id="roleError" class="error-msg">Please select a role.</div>
        </div>


        <div class="d-flex gap-2 align-items-center  justify-content-end">
            <button type="submit" class="btn btn-primary"><?= $userData ? 'Update User' : 'Add User' ?></button>
            <a href="index.php?controller=user&action=index" class="btn btn-secondary ms-2">Cancel</a>
        </div>
    </form>
</div>


<script>
    $(document).ready(function () {

        $('#userForm').on('submit', function (e) {
            let valid = true;

            const username = $('#username').val().trim();
            if (username.length < 3 || username.length > 50) {
                $('#usernameError').show();
                valid = false;
            } else {
                $('#usernameError').hide();
            }

            const email = $('#email').val().trim();
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(email)) {
                $('#emailError').show();
                valid = false;
            } else {
                $('#emailError').hide();
            }

            const password = $('#password').val();
            const isEdit = <?= $userData ? 'true' : 'false' ?>;
            if (!isEdit && password.length < 6) {
                $('#passwordError').show();
                valid = false;
            } else if (isEdit && password.length > 0 && password.length < 6) {
                $('#passwordError').show();
                valid = false;
            } else {
                $('#passwordError').hide();
            }

            if ($('#role').val() === '') {
                $('#roleError').show();
                valid = false;
            } else {
                $('#roleError').hide();
            }

            if (!valid) e.preventDefault();

        });
</script>
<?php $this->view("layouts/footer"); ?>


