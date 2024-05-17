<?php
/*
Napisz skrypt tworzący listę odnośników. Wszystkie adresy wraz z ich
opisami przechowywane będą w pliku tekstowym. Każdy wiersz pliku
będzie miał schematyczną postać (adres;opis):
http://ardes_odnośnika/;opis adresu
*/
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Exercise 3</title>
</head>

<body>
    <?php
    $file_path = getcwd() . '/links.txt';
    if (file_exists($file_path)) {
        $fileContent = file($file_path);
        echo '<ul>';
        foreach ($fileContent as $line) {
            $line = explode(';', $line);
            echo '<li><a href="' . $line[0] . '">' . $line[1] . '</a></li>';
        }
        echo '</ul>';
    } else {
        echo 'File not found.';
    }
    ?>

</html>