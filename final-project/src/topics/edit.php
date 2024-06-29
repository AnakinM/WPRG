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

// Initialize services
$topicService = new TopicService($db, $topicRepository, new SectionRepository($db), new UserRepository($db));

$topic_id = $_GET['id'];
$topic = $topicService->getTopic($topic_id);

if (!$topic) {
    header('Location: /index.php');
    exit();
}

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === Role::Administrator->value;
$isModerator = isset($_SESSION['role']) && $_SESSION['role'] === Role::Moderator->value;
$isOwner = $topic->user_id === $_SESSION['user_id'];

if (!$isAdmin && !$isOwner && !$isModerator) {
    header('Location: /index.php');
    exit();
}

$updateError = '';
$updateSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $content = $_POST['content'];

    try {
        $topicService->updateTopic($topic_id, $title, $content);
        $updateSuccess = 'Topic updated successfully.';
        // Refresh the topic data
        $topic = $topicService->getTopic($topic_id);
    } catch (Exception $e) {
        $updateError = $e->getMessage();
    }
}

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center">Edit Topic</h1>
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
    <form method="post" action="edit.php?id=<?php echo $topic_id; ?>">
        <div class="form-group">
            <label for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title"
                value="<?php echo htmlspecialchars($topic->title); ?>" required>
        </div>
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5"
                required><?php echo htmlspecialchars($topic->content); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Topic</button>
    </form>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>