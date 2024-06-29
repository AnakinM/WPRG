<?php
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/SectionRepository.php';
require_once PRIVATE_PATH . '/services/SectionService.php';
require_once PRIVATE_PATH . '/SessionManager.php';

// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$sectionRepository = new SectionRepository($db);

// Initialize the service
$sectionsService = new SectionService($db, $sectionRepository);

try {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        $id = $_GET['id'];
        $sectionsService->removeSection($id);
    }
    header('Location: /index.php');
    exit();
} catch (Exception $e) {
    // Log the exception
    error_log($e->getMessage());

    // Store error message in session
    session_start();
    $_SESSION['delete_error'] = 'An error occurred while trying to delete the section.';
    header('Location: /index.php');
    exit();
}
?>