<?php
declare(strict_types=1);
class Topic
{
    public int $id;
    public int $section_id;
    public int $user_id;
    public string $title;
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