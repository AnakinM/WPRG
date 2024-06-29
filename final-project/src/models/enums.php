<?php
declare(strict_types=1);
enum Role: string
{
    case Administrator = 'administrator';
    case Moderator = 'moderator';
    case User = 'user';
}