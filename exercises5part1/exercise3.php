<?php
/*
Zmodyfikuj skrypt licznika, który nie będzie uwzględniał przeładowań (odświeżania) strony.
Wykorzystaj pliki cookie.
*/
session_start();
if (!isset($_SESSION['visited'])) {
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
    $_SESSION['visited'] = true;
} else {
    echo 'Witaj na naszej stronie. Odwiedziłeś nas już ' . $_COOKIE['visits'] . ' razy';
}
