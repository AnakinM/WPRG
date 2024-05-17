<?php
/*
Napisz skrypt, w którym użytkownikom łączącym z wybranych
adresów IP zapisanych w pliku tekstowym będzie wyświetlana inna
strona niż wszystkim pozostałym.
Podpowiedź: do sprawdzenia IP można użyć
$_SERVER['REMOTE_ADDR'], a osobne strony (pliki PHP) można
podłączyć poprzez include/require.
*/
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Exercise 4</title>
</head>

<body>
    <?php
    $file_path = getcwd() . '/ip_list.txt';
    if (file_exists($file_path)) {
        $fileContent = file($file_path);
        $user_ip = $_SERVER['REMOTE_ADDR'];
        foreach ($fileContent as $key => $value) {
            $fileContent[$key] = trim($value);
        }
        if (in_array($user_ip, $fileContent)) {
            include 'exercise4a.php';
        } else {
            include 'exercise4b.php';
        }
    } else {
        echo 'File not found.';
    }
    ?>

</html>