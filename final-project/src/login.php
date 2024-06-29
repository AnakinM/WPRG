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

// Initialize the service
$userService = new UserService($db, $userRepository, new UserProfileRepository($db));

$loginError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rememberMe = isset($_POST['remember_me']);

    if ($userService->login($username, $password, $rememberMe)) {
        header('Location: /index.php');
        exit();
    } else {
        $loginError = 'Invalid username or password.';
    }
}

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center">Login</h1>
    <?php if ($loginError): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($loginError); ?>
        </div>
    <?php endif; ?>
    <form method="post" action="login.php">
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="form-group form-check">
            <input type="checkbox" class="form-check-input" id="remember_me" name="remember_me">
            <label class="form-check-label" for="remember_me">Remember Me</label>
        </div>
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>