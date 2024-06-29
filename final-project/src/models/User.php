<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/config.php';
require_once PRIVATE_PATH . '/models/enums.php';

class User
{
    public int $id;
    public string $username;
    public string $password;
    public string $email;
    public Role $role;
    public DateTime $created_at;

    public function __construct()
    {
        unset($this->role);
        unset($this->created_at);
    }

    public function __set($key, $value)
    {
        if ($key === 'role') {
            $this->role = Role::from($value);
        }
        if ($key === 'created_at') {
            $this->created_at = new DateTime($value);
        }
    }
}