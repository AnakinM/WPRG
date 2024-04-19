<?php
$iterations = 0;

function isPrime($number)
{
    global $iterations;
    if ($number < 2) {
        return false;
    }
    if ($number % 2 == 0) {
        return false;
    }
    for ($i = 3; $i <= sqrt($number); $i += 2) {
        $iterations++;
        if ($number % $i == 0) {
            return false;
        }
    }
    return true;
}



$number = 0;
if (isset($_POST['submit'])) {
    $number = $_POST['number'];
    if (is_numeric($number) && $number > 0) {
        if (isPrime($number)) {
            echo "Liczba $number jest liczbą pierwszą";
        } else {
            echo "Liczba $number nie jest liczbą pierwszą";
        }
    }
}
?>
<form action="exercise4.php" method="post">
    <label for="number">Number</label>
    <input type="text" name="number" id="number" value="<?php echo $number ?>">
    <input type="submit" name="submit" value="Check">
</form>
<?php
echo "Iterations: $iterations";
?>
</body>