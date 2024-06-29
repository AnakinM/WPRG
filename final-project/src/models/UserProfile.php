<?php
declare(strict_types=1);
class UserProfile
{
    public int $user_id;
    public ?string $full_name;
    public ?string $bio;
    public ?string $profile_picture;
    public DateTime $created_at;

    public function __construct()
    {
        unset($this->created_at);
    }

    public function __set($key, $value)
    {
        if ($key === 'created_at') {
            $this->created_at = new DateTime($value);
        }
    }
}