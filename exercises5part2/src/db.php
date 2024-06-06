<?php
declare(strict_types=1);
function getDbConnection(): mysqli
{
    $db = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
    if (!$db) {
        throw new Exception('Could not connect: ' . mysqli_connect_error());
    }
    return $db;
}
