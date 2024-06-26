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

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === Role::Administrator->value;
$isOwner = $topic->user_id === $_SESSION['user_id'];

if (!$isAdmin && !$isOwner) {
    header("Location: /topics/view.php?id=$topic_id");
    exit();
}

try {
    $topicService->deleteComment($comment_id);
    header("Location: /topics/view.php?id=$topic_id");
    exit();
} catch (Exception $e) {
    header("Location: /topics/view.php?id=$topic_id&error=Unable to delete comment");
    exit();
}
?>