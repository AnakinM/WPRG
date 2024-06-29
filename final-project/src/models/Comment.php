<?php
declare(strict_types=1);
class Comment
{
    public int $id;
    public int $topic_id;
    public ?int $user_id;
    public ?string $username;
    public ?string $nickname;
    public string $content;
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