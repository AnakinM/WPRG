<?php
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/repositories/UserProfileRepository.php';
require_once PRIVATE_PATH . '/services/UserService.php';
require_once PRIVATE_PATH . '/services/UserProfileService.php';

// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$userRepository = new UserRepository($db);
$userProfileRepository = new UserProfileRepository($db);

// Initialize services
$userService = new UserService($db, $userRepository, $userProfileRepository);
$userProfileService = new UserProfileService($db, $userProfileRepository);

$user_id = $_GET['id'];
$user = $userRepository->get($user_id);
$userProfile = $userProfileRepository->get($user_id);

if (!$user || !$userProfile) {
    header('Location: /index.php');
    exit();
}

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center"><?php echo htmlspecialchars($user->username); ?>'s Profile</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Full Name</h5>
                    <p class="card-text"><?php echo htmlspecialchars($userProfile->full_name); ?></p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Bio</h5>
                    <p class="card-text"><?php echo htmlspecialchars($userProfile->bio); ?></p>
                </div>
            </div>
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title">Profile Picture</h5>
                    <?php if ($userProfile->profile_picture): ?>
                        <img src="<?php echo htmlspecialchars($userProfile->profile_picture); ?>" class="img-fluid"
                            alt="Profile Picture">
                    <?php else: ?>
                        <p>No profile picture.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>