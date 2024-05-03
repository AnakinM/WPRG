<?php
declare(strict_types=1);
/*
Stwórz prosty formularz do obsługi zadania. Napisz funkcję, która
przyjmie jako pierwszy argument ścieżkę (np. "./php/images/network"),
jako drugi nazwę katalogu, a jako trzeci, opcjonalny parametr rodzaj
operacji do wykonania:
-read - odczytanie wszystkich elementów w katalogu (domyślna wartość
parametru);
-delete - usunięcie wskazanego katalogu w podanej ścieżce;
-create - stworzenie katalogu w podanej ścieżce.
Zwróć odpowiedni komunikat (listę elementów lub czy udało się
stworzyć/usunąć katalog).
Przy próbie odczytu pamiętaj o sprawdzeniu, czy dany katalog istnieje, a
przy próbie usunięcia - czy katalog jest pusty i czy istnieje. Pamiętaj
również o sprawdzeniu, czy ostatnim znakiem ścieżki jest "/" - ułatwi to
wykonanie powyższych instrukcji.
*/
?>
<?php
function manageDirectory(string $path, string $directory, string $operation = 'read'): void
{
    if (substr($path, -1) != '/') {
        $path .= '/';
    }
    if ($operation == 'read') {
        if (is_dir($path . $directory)) {
            $elements = scandir($path . $directory);
            echo "Elementy w katalogu $directory:<br>";
            foreach ($elements as $element) {
                echo "$element<br>";
            }
        } else {
            echo "Katalog $directory nie istnieje";
        }
    } elseif ($operation == 'delete') {
        if (is_dir($path . $directory)) {
            if (count(scandir($path . $directory)) == 2) {
                rmdir($path . $directory);
                echo "Katalog $directory został usunięty";
            } else {
                echo "Katalog $directory nie jest pusty";
            }
        } else {
            echo "Katalog $directory nie istnieje";
        }
    } elseif ($operation == 'create') {
        if (!is_dir($path . $directory)) {
            mkdir($path . $directory);
            echo "Katalog $directory został stworzony";
        } else {
            echo "Katalog $directory już istnieje";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Exercise 3</title>
</head>

<body>
    <form method="GET">
        <label for="path">Podaj ścieżkę:</label>
        <input type="text" name="path" id="path">
        <label for="directory">Podaj nazwę katalogu:</label>
        <input type="text" name="directory" id="directory">
        <label for="operation">Wybierz operację:</label>
        <select name="operation" id="operation">
            <option value="read">Odczytaj</option>
            <option value="delete">Usuń</option>
            <option value="create">Stwórz</option>
        </select>
        <input type="submit" value="Wyślij">
    </form>
    <?php
    if (isset($_GET['path']) && isset($_GET['directory'])) {
        $path = $_GET['path'];
        $directory = $_GET['directory'];
        $operation = $_GET['operation'];
        manageDirectory($path, $directory, $operation);
    }
    ?>

</html>