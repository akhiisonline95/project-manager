<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"/>
    <title>Login — Project Management System</title>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <!-- Bootstrap 5 CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet"/>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/login.css"/>
    <!-- jQuery -->
    <script src="assets/js/jquery-3.7.0.min.js"></script>
</head>
<body>
<div class="login-container">
    <div class="avatar mb-2">
        <svg viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
        </svg>
    </div>
    <div class="login-title">Project Management</div>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form id="loginForm" method="post" autocomplete="off" novalidate>
        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input
                    autocomplete="username"
                    type="text"
                    id="username"
                    name="username"
                    class="form-control"
                    required
                    autofocus
                    value="<?= isset($_POST['username']) ? htmlspecialchars($_POST['username']) : 'member1' ?>"
                    placeholder="Enter username"
            />
            <div class="invalid-feedback">
                Please enter your username.
            </div>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input
                    autocomplete="current-password"
                    type="password"
                    id="password"
                    name="password"
                    class="form-control"
                    required
                    value="memberpass"
                    placeholder="Enter password"
            />
            <div class="invalid-feedback">
                Please enter your password.
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 mt-2 mb-3">Sign In</button>
    </form>

    <div class="login-footer">
        <small>
            Test accounts:<br/>
            <strong>admin</strong> / adminpass<br/>
            <strong>member1</strong> / memberpass
        </small>
    </div>
</div>

<script>
    $(document).ready(function () {
        // Custom client-side validation on submit
        $('#loginForm').on('submit', function (e) {
            // Reset validation
            $('#username, #password').removeClass('is-invalid');

            let valid = true;
            if (!$('#username').val().trim()) {
                $('#username').addClass('is-invalid');
                valid = false;
            }
            if (!$('#password').val().trim()) {
                $('#password').addClass('is-invalid');
                valid = false;
            }
            if (!valid) {
                e.preventDefault(); // Prevent form submission if invalid
            }
        });

        // Remove invalid class on input as user types
        $('#username, #password').on('input', function () {
            if ($(this).val().trim()) {
                $(this).removeClass('is-invalid');
            }
        });
    });
</script>

</body>
</html>
