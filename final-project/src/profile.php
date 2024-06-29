<?php
require_once 'config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/repositories/UserProfileRepository.php';
require_once PRIVATE_PATH . '/services/UserService.php';
require_once PRIVATE_PATH . '/services/UserProfileService.php';
require_once PRIVATE_PATH . '/SessionManager.php';

// Ensure the user is logged in
SessionManager::startSession();
if (!isset($_SESSION['user_id'])) {
    header('Location: /login.php');
    exit();
}

// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$userRepository = new UserRepository($db);
$userProfileRepository = new UserProfileRepository($db);

// Initialize services
$userService = new UserService($db, $userRepository, $userProfileRepository);
$userProfileService = new UserProfileService($db, $userProfileRepository);

$user_id = $_SESSION['user_id'];
$userProfile = $userProfileService->getProfile($user_id);

$updateError = '';
$updateSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $_POST['full_name'];
    $bio = $_POST['bio'];
    $profile_picture = $_FILES['profile_picture'];

    try {
        $userProfile = $userProfileService->updateProfile($user_id, $full_name, $bio, $profile_picture);
        $updateSuccess = 'Profile updated successfully.';

    } catch (Exception $e) {
        $updateError = $e->getMessage();
        $userProfile = $userProfileService->getProfile($user_id);
    }
}

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center">Update Profile</h1>
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
    <form method="post" action="profile.php" enctype="multipart/form-data">
        <div class="form-group">
            <label for="full_name">Full Name</label>
            <input type="text" class="form-control" id="full_name" name="full_name"
                value="<?php echo $userProfile->full_name ? htmlspecialchars($userProfile->full_name) : ''; ?>"
                required>
        </div>
        <div class="form-group">
            <label for="bio">Bio</label>
            <textarea class="form-control" id="bio" name="bio" rows="5"
                required><?php echo $userProfile->bio ? htmlspecialchars($userProfile->bio) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="profile_picture">Profile Picture</label>
            <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>