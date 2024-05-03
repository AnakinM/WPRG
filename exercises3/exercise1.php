<?php
/*
Utwórz prosty formularz, który pozwoli na wybranie daty urodzenia.
Dane powinny zostać przesłane za pomocą metody GET. Po podaniu
daty przez użytkownika, należy za pomocą osobnych funkcji sprawdzić i
wyświetlić następujące informacje:
-w jaki dzień tygodnia urodził się użytkownik;
-ukończone lata użytkownika;
-ilość dni do najbliższych, przyszłych urodzin
*/
?>
<!DOCTYPE html>
<html lang="pl">

<head>
    <meta charset="UTF-8">
    <title>Exercise 1</title>
</head>

<body>
    <form method="GET">
        <label for="date">Podaj datę urodzenia:</label>
        <input type="date" name="date" id="date">
        <input type="submit" value="Wyślij">
    </form>
    <?php
    if (isset($_GET['date'])) {
        $date = $_GET['date'];
        $day = date('l', strtotime($date));
        $years = date('Y') - date('Y', strtotime($date));
        $nextBirthday = date('z', strtotime(date('Y') . '-' . date('m') . '-' . date('d'))) - date('z', strtotime($date));
        if ($nextBirthday < 0) {
            $nextBirthday = 365 + $nextBirthday;
        }
        echo "Urodziłeś się w dniu tygodnia: $day<br>";
        echo "Masz $years lat<br>";
        echo "Do Twoich następnych urodzin pozostało $nextBirthday dni";
    }
    ?>
</body>

</html>