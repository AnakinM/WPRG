<?php
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/CommentRepository.php';
require_once PRIVATE_PATH . '/services/CommentService.php';
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
$topicRepository = new CommentRepository($db);

// Initialize services
$topicService = new CommentService($db, $topicRepository, new UserRepository($db));

$comment_id = $_GET['id'];
$topic_id = $_GET['topic_id'];
$topic = $topicService->getComment($comment_id);

if (!$topic) {
    header("Location: /topics/view.php?id=$topic_id");
    exit();
}

$isOwner = $topic->user_id === $_SESSION['user_id'];

if (!isAdmin() && !$isOwner && !isModerator()) {
    header("Location: /topics/view.php?id=$topic_id");
    exit();
}

$updateError = '';
$updateSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = $_POST['content'];

    try {
        $topic = $topicService->updateComment($comment_id, $content);
        $updateSuccess = 'Comment updated successfully.';
    } catch (Exception $e) {
        $updateError = $e->getMessage();
        $topic = $topicService->getComment($comment_id);
    }
}

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center">Edit Comment</h1>
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
    <form method="post" action="edit.php?id=<?php echo $comment_id; ?>&topic_id=<?php echo $topic_id; ?>">
        <div class="form-group">
            <label for="content">Content</label>
            <textarea class="form-control" id="content" name="content" rows="5"
                required><?php echo $topic ? htmlspecialchars($topic->content) : ""; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Comment</button>
    </form>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>