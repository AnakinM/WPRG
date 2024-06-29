<?php
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/services/UserService.php';
require_once PRIVATE_PATH . '/SessionManager.php';

// Ensure the user is logged in and is an administrator
SessionManager::startSession();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== Role::Administrator->value) {
    header('Location: /index.php');
    exit();
}

// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$userRepository = new UserRepository($db);

// Initialize the service
$userService = new UserService($db, $userRepository, new UserProfileRepository($db));

// Handle role update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['role'])) {
    $user_id = (int) $_POST['user_id'];
    $role = $_POST['role'];
    $userService->updateUserRole($user_id, Role::from($role));
}

// Handle user deletion
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user_id'])) {
    $user_id = (int) $_POST['delete_user_id'];
    $userService->deleteUser($user_id);
}

// Retrieve all users
$users = $userRepository->getAll();

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center">User Management</h1>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user->username); ?></td>
                    <td><?php echo htmlspecialchars($user->email); ?></td>
                    <td>
                        <form method="post" class="form-inline">
                            <input type="hidden" name="user_id" value="<?php echo $user->id; ?>">
                            <select name="role" class="form-control">
                                <option value="<?php echo Role::Administrator->value; ?>" <?php echo $user->role === Role::Administrator ? 'selected' : ''; ?>>Administrator</option>
                                <option value="<?php echo Role::Moderator->value; ?>" <?php echo $user->role === Role::Moderator ? 'selected' : ''; ?>>Moderator</option>
                                <option value="<?php echo Role::User->value; ?>" <?php echo $user->role === Role::User ? 'selected' : ''; ?>>User</option>
                            </select>
                            <button type="submit" class="btn btn-primary ml-2">Update Role</button>
                        </form>
                    </td>
                    <td>
                        <form method="post">
                            <input type="hidden" name="delete_user_id" value="<?php echo $user->id; ?>">
                            <button type="submit" class="btn btn-danger"
                                onclick="return confirm('Are you sure you want to delete this user?');">Delete User</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>