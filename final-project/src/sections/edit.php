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

$editError = '';
$editSuccess = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $description = $_POST['description'];

    try {
        $section = $sectionsService->updateSection($id, $name, $description);
        if ($section) {
            $editSuccess = 'Section updated successfully.';
        } else {
            $editError = 'Failed to update section.';
        }
    } catch (Exception $e) {
        $editError = $e->getMessage();
        $section = $sectionRepository->get($id);
    }
} else {
    $id = $_GET['id'];
    $section = $sectionRepository->get($id);
    if (!$section) {
        header('Location: /index.php');
        exit();
    }
}

include TEMPLATES_PATH . '/header.php';
include TEMPLATES_PATH . '/navbar.php';
?>

<div class="container mt-5">
    <h1 class="text-center">Edit Section</h1>
    <?php if ($editError): ?>
        <div class="alert alert-danger" role="alert">
            <?php echo htmlspecialchars($editError); ?>
        </div>
    <?php endif; ?>
    <?php if ($editSuccess): ?>
        <div class="alert alert-success" role="alert">
            <?php echo htmlspecialchars($editSuccess); ?>
        </div>
    <?php endif; ?>
    <form method="post" action="edit.php">
        <input type="hidden" name="id" value="<?php echo $section ? htmlspecialchars($section->id) : ""; ?>">
        <div class="form-group">
            <label for="name">Section Name</label>
            <input type="text" class="form-control" id="name" name="name"
                value="<?php echo $section ? htmlspecialchars($section->name) : ""; ?>" required>
        </div>
        <div class="form-group">
            <label for="description">Section Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"
                required><?php echo $section ? htmlspecialchars($section->description) : ""; ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Update Section</button>
    </form>
</div>

<?php
include TEMPLATES_PATH . '/footer.php';
?>