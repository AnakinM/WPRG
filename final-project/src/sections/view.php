<?php
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/SectionRepository.php';
require_once PRIVATE_PATH . '/repositories/TopicRepository.php';
require_once PRIVATE_PATH . '/services/SectionService.php';
require_once PRIVATE_PATH . '/services/TopicService.php';
require_once PRIVATE_PATH . '/SessionManager.php';
SessionManager::startSession();
// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$sectionRepository = new SectionRepository($db);
$topicRepository = new TopicRepository($db);

// Initialize services
$sectionService = new SectionService($db, $sectionRepository);
$topicService = new TopicService($db, $topicRepository, $sectionRepository, new UserRepository($db));

$section_id = $_GET['id'];
$section = $sectionService->getSection($section_id);
$topics = $topicService->getAllTopicsBySection($section_id);

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center"><?php echo htmlspecialchars($section->name); ?></h1>
    <p class="text-center"><?php echo htmlspecialchars($section->description); ?></p>
    <hr>
    <h3>Topics</h3>
    <?php if ($topics): ?>
        <ul class="list-group">
            <?php foreach ($topics as $topic): ?>
                <li class="list-group-item">
                    <a href="/topics/view.php?id=<?php echo $topic->id; ?>"><?php echo htmlspecialchars($topic->title); ?></a>
                    <p><?php echo htmlspecialchars($topic->content); ?></p>
                    <small>By <?php echo htmlspecialchars($topic->user_id); ?> on
                        <?php echo $topic->created_at->format('Y-m-d H:i:s'); ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No topics yet.</p>
    <?php endif; ?>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>