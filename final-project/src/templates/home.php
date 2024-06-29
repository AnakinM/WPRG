<?php
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/SectionRepository.php';
require_once PRIVATE_PATH . '/repositories/TopicRepository.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/services/SectionService.php';
require_once PRIVATE_PATH . '/services/TopicService.php';

// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$sectionRepository = new SectionRepository($db);
$topicRepository = new TopicRepository($db);

// Initialize services
$sectionsService = new SectionService($db, $sectionRepository);
$topicService = new TopicService($db, $topicRepository, $sectionRepository, new UserRepository($db));

// Retrieve all sections
$sections = $sectionsService->getAllSections();

// Retrieve the latest topics for each section
$latestTopicsBySection = $topicService->getLatestTopicsBySection();

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';

if (isset($_SESSION['delete_error'])): ?>
    <div class="alert alert-danger" role="alert">
        <?php echo htmlspecialchars($_SESSION['delete_error']);
        unset($_SESSION['delete_error']); ?>
    </div>
<?php endif; ?>

<div class="container mt-5">
    <h1 class="text-center">GameDev Forum</h1>
    <?php foreach ($sections as $section): ?>
        <div class="section mt-4">
            <h2><a
                    href="/sections/view.php?id=<?php echo $section->id; ?>"><?php echo htmlspecialchars($section->name); ?></a>
            </h2>
            <p><?php echo htmlspecialchars($section->description); ?></p>
            <?php if (isset($_SESSION['username'])): ?>
                <a href="/topics/new.php?section_id=<?php echo $section->id; ?>" class="btn btn-success btn-sm">Add New
                    Topic</a>
            <?php endif; ?>
            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === Role::Administrator->value): ?>
                <a href="/sections/edit.php?id=<?php echo $section->id; ?>" class="btn btn-primary btn-sm">Edit Section</a>
                <a href="/sections/delete.php?id=<?php echo $section->id; ?>" class="btn btn-danger btn-sm"
                    onclick="return confirm('Are you sure you want to delete this section?');">Delete Section</a>
            <?php endif; ?>
            <div class="latest-topic mt-3">
                <h4>Latest Topic</h4>
                <?php if (isset($latestTopicsBySection[$section->id])): ?>
                    <h5>
                        <a href="/topics/view.php?id=<?php echo $latestTopicsBySection[$section->id]['id']; ?>">
                            <?php echo htmlspecialchars($latestTopicsBySection[$section->id]['title']); ?>
                        </a>
                    </h5>
                    <p><?php echo htmlspecialchars($latestTopicsBySection[$section->id]['content']); ?></p>
                    <small>By <?php echo htmlspecialchars($latestTopicsBySection[$section->id]['username']); ?> on
                        <?php echo (new DateTime($latestTopicsBySection[$section->id]['created_at']))->format('Y-m-d H:i:s'); ?></small>
                <?php else: ?>
                    <p>No topics yet.</p>
                <?php endif; ?>
            </div>
        </div>
        <hr>
    <?php endforeach; ?>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>