<?php
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/SectionRepository.php';
require_once PRIVATE_PATH . '/services/SectionService.php';
require_once PRIVATE_PATH . '/SessionManager.php';
SessionManager::startSession();
// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$sectionRepository = new SectionRepository($db);

// Initialize the service
$sectionsService = new SectionService($db, $sectionRepository);

$createError = '';
$createSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];

    try {
        if ($sectionsService->createSection($name, $description)) {
            $createSuccess = 'Section created successfully.';
        } else {
            $createError = 'Section with this name already exists.';
        }
    } catch (Exception $e) {
        $createError = $e->getMessage();
    }
}

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center">Add New Section</h1>
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
    <form method="post" action="new.php">
        <div class="form-group">
            <label for="name">Section Name</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="description">Section Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Create Section</button>
    </form>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>