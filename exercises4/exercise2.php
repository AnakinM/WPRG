<?php
/*
Napisz skrypt ukazujący liczbę odwiedzin witryny. Dane powinny być
zapisywane w postaci tekstowej w pliku licznik.txt. Każde wywołanie
skryptu będzie powodowało otwarcie tego pliku, odczyt znajdujących się
w nim danych, zwiększenie odczytanej wartości o jeden i ponowny zapis
– zaktualizowanych już danych – do pliku. Upewnij się, że plik istnieje -
jeśli nie, stwórz go i ustaw liczbę odwiedzin na 1.
*/
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Exercise 2</title>
</head>

<body>
    <?php
    $file_path = getcwd() . '/licznik.txt';
    if (!file_exists($file_path)) {
        file_put_contents($file_path, 1);
        $fileContent = file_get_contents($file_path);
    } else {
        $fileContent = file_get_contents($file_path);
        $fileContent++;
        file_put_contents($file_path, $fileContent);
    }
    echo 'Number of visits: ' . $fileContent;
    ?>
</body>

</html>