<?php
require_once 'config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/repositories/UserProfileRepository.php';
require_once PRIVATE_PATH . '/services/UserService.php';
require_once PRIVATE_PATH . '/SessionManager.php';

// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$userRepository = new UserRepository($db);
$userProfileRepository = new UserProfileRepository($db);

// Initialize the service
$userService = new UserService($db, $userRepository, $userProfileRepository);

$registrationError = '';
$registrationSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $email = $_POST['email'];

    if ($password !== $confirm_password) {
        $registrationError = 'Passwords do not match.';
    } else {
        if ($userService->createUser($username, $password, $email)) {
            $registrationSuccess = 'Account created successfully. You can now log in.';
        } else {
            $registrationError = 'Username or email already exists.';
        }
    }
}

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center">Register</h1>
    <?php if ($registrationError): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($registrationError); ?>
        </div>
    <?php endif; ?>
    <?php if ($registrationSuccess): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($registrationSuccess); ?>
        </div>
    <?php endif; ?>
    <form method="post" action="register.php" onsubmit="return validatePassword()">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group">
            <label for="confirm_password">Confirm Password</label>
            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
        </div>
        <div id="passwordError" class="text-danger"></div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>

<script>
    function validatePassword() {
        var password = document.getElementById("password").value;
        var confirmPassword = document.getElementById("confirm_password").value;
        var passwordError = document.getElementById("passwordError");

        if (password !== confirmPassword) {
            passwordError.textContent = "Passwords do not match.";
            return false;
        } else {
            passwordError.textContent = "";
            return true;
        }
    }
</script>