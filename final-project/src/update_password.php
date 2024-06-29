<?php
require_once 'config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/services/UserService.php';
require_once PRIVATE_PATH . '/SessionManager.php';

SessionManager::startSession();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$userRepository = new UserRepository($db);

// Initialize the service
$userService = new UserService($db, $userRepository, new UserProfileRepository($db));

$updateError = '';
$updateSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password !== $confirm_password) {
        $updateError = 'New passwords do not match.';
    } else {
        try {
            $user_id = $_SESSION['user_id'];
            if ($userService->updatePassword($user_id, $current_password, $new_password)) {
                $updateSuccess = 'Password updated successfully.';
                $userService->logout();
                header('Location: /login.php');
            } else {
                $updateError = 'Current password is incorrect.';
            }
        } catch (Exception $e) {
            $updateError = $e->getMessage();
        }
    }
}

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center">Update Password</h1>
    <?php if ($updateError): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($updateError); ?>
        </div>
    <?php endif; ?>
    <?php if ($updateSuccess): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($updateSuccess); ?>
        </div>
    <?php endif; ?>
    <form method="post" action="update_password.php">
        <div class="form-group">
            <label for="current_password">Current Password</label>
            <input type="password" class="form-control" id="current_password" name="current_password" required>
        </div>
        <div class="form-group">
            <label for="new_password">New Password</label>
            <input type="password" class="form-control" id="new_password" name="new_password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm New Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary">Update Password</button>
    </form>
</div>

<script>
    function validatePassword() {
        var newPassword = document.getElementById("new_password").value;
        var confirmPassword = document.getElementById("confirm_password").value;
        var passwordError = document.getElementById("passwordError");

        if (newPassword !== confirmPassword) {
            passwordError.textContent = "Passwords do not match.";
            return false;
        } else {
            passwordError.textContent = "";
            return true;
        }
    }
</script>

<?php
include TEMPLATES_PATH . '/footer.php';
?>