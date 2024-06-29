<?php
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/TopicRepository.php';
require_once PRIVATE_PATH . '/repositories/SectionRepository.php';
require_once PRIVATE_PATH . '/services/TopicService.php';
require_once PRIVATE_PATH . '/SessionManager.php';
require_once PRIVATE_PATH . '/utils.php';

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

// Initialize services
$topicService = new TopicService($db, $topicRepository, $sectionRepository, new UserRepository($db));

$topic_id = $_GET['id'];
$topic = $topicService->getTopic($topic_id);

if (!$topic) {
    header("Location: /index.php");
    exit();
}

$isOwner = $topic->user_id === $_SESSION['user_id'];

if (!isAdmin() && !$isOwner) {
    header("Location: /topics/view.php?id=$topic_id");
    exit();
}

try {
    $topicService->removeTopic($topic_id);
    header("Location: /topics/view.php?id=$topic_id");
    exit();
} catch (Exception $e) {
    header("Location: /topics/view.php?id=$topic_id&error=Unable to delete topic");
    exit();
}
?>