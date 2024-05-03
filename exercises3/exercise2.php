<?php
/*
Wybierz jeden z dwóch algorytmów: liczenie silni lub dowolny wyraz
ciągu Fibonacciego. Napisz funkcję rekurencyjną oraz jej zwykły
odpowiednik (nierekurencyjny) dla wybranego algorytmu. Obie funkcje
powinny przyjmować stosowny argument. Następnie zmierz działanie
obu funkcji dla argumentu podanego przez użytkownika i wyświetl
informacje o tym, która funkcja i o ile działała szybciej.
*/
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Exercise 2</title>
</head>

<body>
    <form method="GET">
        <label for="number">Podaj liczbę:</label>
        <input type="number" name="number" id="number">
        <input type="submit" value="Wyślij">
    </form>
    <?php
    if (isset($_GET['number'])) {
        $number = $_GET['number'];
        $start = microtime(true);
        echo "Silnia rekurencyjnie: " . factorialRecursive($number) . "<br>";
        $end = microtime(true);
        $timeRecursive = $end - $start;
        $start = microtime(true);
        echo "Silnia nierekurencyjnie: " . factorialNonRecursive($number) . "<br>";
        $end = microtime(true);
        $timeNonRecursive = $end - $start;
        if ($timeRecursive < $timeNonRecursive) {
            echo "Funkcja rekurencyjna działała szybciej o " . ($timeNonRecursive - $timeRecursive) . " sekund";
        } else {
            echo "Funkcja nierekurencyjna działała szybciej o " . ($timeRecursive - $timeNonRecursive) . " sekund";
        }
    }
    function factorialRecursive($number)
    {
        if ($number == 0) {
            return 1;
        } else {
            return $number * factorialRecursive($number - 1);
        }
    }
    function factorialNonRecursive($number)
    {
        $result = 1;
        for ($i = 1; $i <= $number; $i++) {
            $result *= $i;
        }
        return $result;
    }
    ?>

</html>