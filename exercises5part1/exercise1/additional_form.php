<?php
/* gather data data for each additional person from people_count  */
session_start();
if (!isset($_SESSION['data'])) {
    header('Location: exercise1.php');
}
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include 'templates/header.html';
    echo '<form action="additional_form.php" method="post">';
    for ($i = 0; $i < $_SESSION['people_count']; $i++) {
        echo '<label>';
        echo 'Imię:';
        echo '<input type="text" name="people[' . $i . '][name]" required>';
        echo '</label>';
        echo '<label>';
        echo 'Nazwisko:';
        echo '<input type="text" name="people[' . $i . '][surname]" required>';
        echo '</label>';
    }
    echo '<button type="submit">Zapisz</button>';
    echo '</form>';
    include 'templates/footer.html';
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['data'] = array_merge($_SESSION['data'], $_POST);
    header('Location: summary.php');
    exit;
}
