<?php
require_once 'config.php';
require_once PRIVATE_PATH . '/services/UserService.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/repositories/UserProfileRepository.php';

// Initialize the database connection
$db = new DB(DSN, USERNAME, PASSWORD);

// Initialize repositories
$userRepository = new UserRepository($db);

// Initialize the service
$userService = new UserService($db, $userRepository, new UserProfileRepository($db));

// Logout the user
$userService->logout();

// Redirect to the homepage
header('Location: /index.php');
exit();
?>