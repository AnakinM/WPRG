<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/repositories/UserRepository.php';
require_once PRIVATE_PATH . '/repositories/UserProfileRepository.php';
require_once PRIVATE_PATH . '/models/enums.php';
require_once PRIVATE_PATH . '/SessionManager.php';

class UserService
{
    private DB $db;
    private UserRepository $userRepository;
    private UserProfileRepository $userProfileRepository;

    public function __construct(DB $db, UserRepository $userRepository, UserProfileRepository $userProfileRepository)
    {
        $this->db = $db;
        $this->userRepository = $userRepository;
        $this->userProfileRepository = $userProfileRepository;
    }

    public function createUser(string $username, string $password, string $email): bool
    {
        if ($this->userRepository->getBy('username', $username) || $this->userRepository->getBy('email', $email)) {
            return false; // User already exists
        }

        $this->db->beginTransaction();

        try {
            $this->userRepository->create($username, $password, $email, Role::User);
            $user = $this->userRepository->getBy('username', $username);

            if ($user) {
                $this->userProfileRepository->create($user->id, '', '', '');
            }

            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function getUser(int $user_id): ?User
    {
        return $this->userRepository->get($user_id);
    }

    public function login(string $username, string $password, bool $rememberMe = false): bool
    {
        $user = $this->userRepository->getBy('username', $username);

        if ($user && password_verify($password, $user->password)) {
            SessionManager::startSession();
            $_SESSION['user_id'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['role'] = $user->role->value;

            if ($rememberMe) {
                $token = bin2hex(random_bytes(16));
                setcookie('remember_me', $token, time() + (86400 * 30));
                $this->userRepository->updateRememberToken($user->id, $token);
            }

            return true;
        }

        return false;
    }

    public function logout(): void
    {
        SessionManager::startSession();
        session_unset();
        session_destroy();
        setcookie('remember_me', '', time() - 3600);
    }

    public function removeAccount(int $user_id): void
    {
        $this->db->beginTransaction();

        try {
            $this->logout(); // Destroy user session
            $this->userProfileRepository->delete($user_id); // Delete user profile
            $this->userRepository->delete($user_id); // Delete user
            $this->db->commit();
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function updateUserRole(int $user_id, Role $role): bool
    {
        $this->db->beginTransaction();

        try {
            $user = $this->userRepository->get($user_id);

            if (!$user) {
                return false;
            }

            $user->role = $role;
            $this->userRepository->update($user->id, null, null, $role);
            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function deleteUser(int $user_id): bool
    {
        $this->db->beginTransaction();

        try {
            $this->userProfileRepository->delete($user_id);
            $this->userRepository->delete($user_id);
            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function updatePassword(int $user_id, string $current_password, string $new_password): bool
    {
        $this->db->beginTransaction();

        try {
            $user = $this->userRepository->get($user_id);

            if (!$user || !password_verify($current_password, $user->password)) {
                return false;
            }

            $this->userRepository->update($user_id, $new_password, null, null);
            $this->db->commit();
            return true;
        } catch (\Throwable $e) {
            $this->db->rollback();
            throw $e;
        }
    }

    public function checkRememberMe(): void
    {
        if (isset($_COOKIE['remember_me'])) {
            $token = $_COOKIE['remember_me'];
            $user = $this->userRepository->getByRememberToken($token);

            if ($user) {
                SessionManager::startSession();
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                $_SESSION['role'] = $user->role->value;
            }
        }
    }
}
?>