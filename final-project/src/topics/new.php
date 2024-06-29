<?php
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/TopicRepository.php';
require_once PRIVATE_PATH . '/services/TopicService.php';
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
$topicRepository = new TopicRepository($db);
$sectionRepository = new SectionRepository($db);

// Initialize the service
$topicService = new TopicService($db, $topicRepository, $sectionRepository, new UserRepository($db));

$section_id = $_GET['section_id'];
$createError = '';
$createSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $user_id = $_SESSION['user_id'];

    try {
        if ($topicService->createTopic($section_id, $user_id, $title, $content)) {
            $createSuccess = 'Topic created successfully.';
        } else {
            $createError = 'Failed to create topic.';
        }
    } catch (Exception $e) {
        $createError = $e->getMessage();
    }
}

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center">Add New Topic</h1>
    <?php if ($createError): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($createError); ?>
        </div>
    <?php endif; ?>
    <?php if ($createSuccess): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($createSuccess); ?>
        </div>
    <?php endif; ?>
    <form method="post" action="new.php?section_id=<?php echo htmlspecialchars($section_id); ?>">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Topic</button>
    </form>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>