<?php
declare(strict_types=1);
define('DSN', 'mysql:host=db;dbname=forumdb');
define('USERNAME', 'admin');
define('PASSWORD', 'admin');
define("PRIVATE_PATH", dirname(__FILE__));
define("PROJECT_PATH", dirname(PRIVATE_PATH) . '/html');
define("TEMPLATES_PATH", PROJECT_PATH . '/templates');
define("SESSION_TIMEOUT", 3600);  // 1 hour

?>