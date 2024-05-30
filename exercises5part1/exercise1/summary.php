<?php
session_start();
if (!isset($_SESSION['data'])) {
    header('Location: exercise1.php');
}
include 'templates/header.html';
echo '<h2>Podsumowanie</h2>';
echo '<ul>';
foreach ($_SESSION['data'] as $key => $value) {
    if (is_array($value)) {
        echo '<li>';
        echo $key . ':';
        echo '<ul>';
        foreach ($value as $k => $v) {
            if (is_array($v)) {
                echo '<li>';
                echo $k . ':';
                echo '<ul>';
                foreach ($v as $kk => $vv) {
                    echo '<li>';
                    echo $kk . ': ' . $vv;
                    echo '</li>';
                }
                echo '</ul>';
                echo '</li>';
            } else {
                echo '<li>';
                echo $k . ': ' . $v;
                echo '</li>';
            }
        }
        echo '</ul>';
        echo '</li>';
    } else {
        echo '<li>';
        echo $key . ': ' . $value;
        echo '</li>';
    }
}
echo '</ul>';
include 'templates/footer.html';
