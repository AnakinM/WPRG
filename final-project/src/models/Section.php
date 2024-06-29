<?php
declare(strict_types=1);
class Section
{
    public int $id;
    public string $name;
    public string $description;
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