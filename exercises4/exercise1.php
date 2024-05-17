<?php
/*
Napisz skrypt, który odwróci kolejność wierszy w pliku tekstowym (tzn.
ostatni wiersz ma się stać pierwszym, przedostatni drugim itd…). Do
wykonania zadania stwórz prosty formularz z możliwością wyboru pliku
(<INPUT TYPE=”FILE”>).
*/
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Exercise 1</title>
</head>

<body>
    <form action="exercise1.php" method="post" enctype="multipart/form-data">
        <input type="file" name="file">
        <input type="submit" value="Send">
    </form>
    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['file'])) {
            $file = $_FILES['file'];
            if ($file['error'] === 0) {
                $fileContent = file($file['tmp_name']);
                $fileContent = array_reverse($fileContent);
                $file_path = getcwd() . '/reversed_' . $file['name'];
                file_put_contents($file_path, $fileContent);
                echo 'File content reversed. And vaved in file: ' . $file_path . '<br>';
            } else {
                echo 'Error while uploading file.';
            }
        }
    }
    ?>
</body>

</html>