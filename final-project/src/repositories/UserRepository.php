<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/DB.php';
require_once PRIVATE_PATH . '/models/User.php';
class UserRepository
{
    private DB $db;

    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    public function create(string $username, string $password, string $email, Role $role = Role::User): void
    {
        $this->db->run(
            "INSERT INTO users (username, password, email, role) VALUES (:username, :password, :email, :role)",
            [
                'username' => $username,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'email' => $email,
                'role' => $role->value
            ]
        );
    }

    public function get(int $id): ?User
    {
        $stmt = $this->db->run(
            "SELECT * FROM users WHERE id = :id LIMIT 1",
            ['id' => $id]
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        $user = $stmt->fetch();
        return $user ? $user : NULL;
    }

    public function getBy(string $column, $value): ?User
    {
        $stmt = $this->db->run(
            "SELECT * FROM users WHERE $column = :value LIMIT 1",
            ['value' => $value]
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        $user = $stmt->fetch();
        return $user ? $user : NULL;
    }

    /**
     * @return User[]
     */
    public function getAll(): array
    {
        $users = $this->db->run("SELECT * FROM users")->fetchAll(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        return $users ? $users : [];
    }

    public function update(int $id, ?string $password = NULL, ?string $email = NULL, ?Role $role = NULL): ?User
    {
        $user = $this->get($id);
        if (!$user) {
            return NULL;
        }

        $params = ['id' => $id];
        $fields = [];

        if ($password !== null) {
            $params['password'] = password_hash($password, PASSWORD_DEFAULT);
            $fields[] = 'password = :password';
        }

        if ($email !== null) {
            $params['email'] = $email;
            $fields[] = 'email = :email';
        }

        if ($role !== null) {
            $params['role'] = $role->value;
            $fields[] = 'role = :role';
        }

        if (empty($fields)) {
            return $user; // No fields to update
        }

        $sql = sprintf(
            "UPDATE users SET %s WHERE id = :id",
            implode(', ', $fields)
        );

        $stmt = $this->db->run($sql, $params);
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        $user = $stmt->fetch();
        return $user ? $user : NULL;
    }

    public function delete(int $id): void
    {
        $this->db->run(
            "DELETE FROM users WHERE id = :id",
            ['id' => $id]
        );
    }

    public function getAdmin(): ?User
    {
        return $this->getBy('role', Role::Administrator->value);
    }

    public function updateRememberToken(int $id, string $token): void
    {
        $this->db->run(
            "UPDATE users SET remember_token = :token WHERE id = :id",
            [
                'id' => $id,
                'token' => $token
            ]
        );
    }

    public function getByRememberToken(string $token): ?User
    {
        $stmt = $this->db->run(
            "SELECT * FROM users WHERE remember_token = :token",
            ['token' => $token]
        );
        $stmt->setFetchMode(PDO::FETCH_CLASS | PDO::FETCH_PROPS_LATE, User::class);
        return $stmt->fetch();
    }
}