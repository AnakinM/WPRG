<?php
declare(strict_types=1);
require_once 'config.php';
require_once PRIVATE_PATH . '/models/enums.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/DB.php';

$db = new DB(DSN, USERNAME, PASSWORD);
$userRepository = new UserRepository($db);

function isAdmin(?int $user_id = NULL): bool
{
    if ($user_id) {
        global $userRepository;
        $user = $userRepository->get($user_id);
        return $user->role === Role::Administrator->value;
    }
    return isset($_SESSION['role']) && $_SESSION['role'] === Role::Administrator->value;
}

function isModerator(?int $user_id = NULL): bool
{
    if ($user_id) {
        global $userRepository;
        $user = $userRepository->get($user_id);
        return $user->role === Role::Moderator->value;
    }
    return isset($_SESSION['role']) && $_SESSION['role'] === Role::Moderator->value;
}
?>