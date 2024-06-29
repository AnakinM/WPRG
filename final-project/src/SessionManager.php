<?php
declare(strict_types=1);
require_once 'config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/repositories/UserProfileRepository.php';
require_once PRIVATE_PATH . '/services/UserService.php';

class SessionManager
{
    private const TIMEOUT_DURATION = SESSION_TIMEOUT;

    public static function startSession(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();

            // Check for remember me cookie if user is not already logged in
            if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
                $db = new DB(DSN, USERNAME, PASSWORD);
                $userRepository = new UserRepository($db);
                $userService = new UserService($db, $userRepository, new UserProfileRepository($db));
                $userService->checkRememberMe();
            }
        }

        // Check for timeout
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > self::TIMEOUT_DURATION)) {
            session_unset();
            session_destroy();
            session_start();
        }

        $_SESSION['last_activity'] = time(); // Update last activity time
    }
}
?>