<?php
/*
Zadanie 1
1. Pierwsza podstrona ma pobierać dane ogólne np. nr karty, dane zamawiającego,
ilość osób itp.
2. Druga podstrona w zależności od ilości osób pozwala pobrać ich dane (pobrano na
pierwszej podstronie 3 osoby, druga podstrona pozwala pobrać dane od 3 osób).
Dodatkowo 2 przyciski zapisujący dane w sesji i pozwalający przejść do podstrony
trzeciej.
3. Trzecia podstrona wyświetla podsumowanie wszystkich zebranych danych.
*/
session_start();
if (!isset($_SESSION['data'])) {
    $_SESSION['data'] = [];
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['data'] = array_merge($_SESSION['data'], $_POST);
    if ($_POST['people_count'] > 0) {
        $_SESSION['people_count'] = $_POST['people_count'];
        header('Location: additional_form.php');
        exit;
    } else {
        header('Location: summary.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Exercise 1</title>
</head>

<body>
    <form method="post">
        <label>
            Numer karty:
            <input type="text" name="card_number" required>
        </label>
        <label>
            Imię:
            <input type="text" name="name" required>
        </label>
        <label>
            Nazwisko:
            <input type="text" name="surname" required>
        </label>
        <label>
            Ilość osób:
            <input type="number" name="people_count" required>
        </label>
        <button type="submit">Zapisz</button>
    </form>
</body>

</html>