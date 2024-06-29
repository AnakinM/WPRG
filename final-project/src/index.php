<?php
declare(strict_types=1);
require_once 'config.php';
require_once 'DB.php';
require_once 'SessionManager.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';

// create admin if not exists
$db = new DB(DSN, USERNAME, PASSWORD);
$userRepository = new UserRepository($db);
$admin = $userRepository->getAdmin();
if (!$admin) {
    $userRepository->create('admin', 'admin', 'admin@example.com', Role::Administrator);
}

// Start session
SessionManager::startSession();

// Render homepage
include 'templates/home.php';
?>