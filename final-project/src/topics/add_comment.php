<?php
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/CommentRepository.php';
require_once PRIVATE_PATH . '/services/CommentService.php';
require_once PRIVATE_PATH . '/SessionManager.php';
SessionManager::startSession();
// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$commentRepository = new CommentRepository($db);

// Initialize the service
$commentService = new CommentService($db, $commentRepository, new UserRepository($db));
if (!isset($_SESSION['user_id']) && !isset($_POST['nickname'])) {
    header('Location: /login.php');
    exit();
}
$topic_id = $_GET['topic_id'];
$content = $_POST['content'];
$nickname = isset($_POST['nickname']) ? $_POST['nickname'] . " (Unauthenticated user)" : null;
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

try {
    $commentService->addComment($topic_id, $user_id, $nickname, $content);
    header("Location: view.php?id=$topic_id");
    exit();
} catch (Exception $e) {
    // Handle exception (e.g., log it, display an error message, etc.)
    header("Location: view.php?id=$topic_id&error=Unable to add comment");
    exit();
}
?>