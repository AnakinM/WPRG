<?php
/*
Napisz skrypt, który za pomocą cookies zlicza liczbę odwiedzin strony przez danego
użytkownika i po osiągnięciu zadanej wartości wyświetla stosowną informację
*/
if (!isset($_COOKIE['visits'])) {
    setcookie('visits', 1, time() + (86400 * 365));
    echo 'Witaj pierwszy raz na naszej stronie';
} else {
    $visits = $_COOKIE['visits'] + 1;
    setcookie('visits', $visits, time() + (86400 * 365));
    if ($visits === 5) {
        echo 'Witaj po raz piąty na naszej stronie';
    } else {
        echo 'Witaj na naszej stronie. Odwiedziłeś nas już ' . $visits . ' razy';
    }
}