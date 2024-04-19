<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <?php
    $result = "";
    if (isset($_POST['submit'])) {
        $num1 = $_POST['num1'];
        $num2 = $_POST['num2'];
        $operator = $_POST['operator'];

        switch ($operator) {
            case 'add':
                $result = $num1 + $num2;
                break;
            case 'subtract':
                $result = $num1 - $num2;
                break;
            case 'multiply':
                $result = $num1 * $num2;
                break;
            case 'divide':
                $result = $num1 / $num2;
                break;
            default:
                $result = "Error";
                break;
        }
    }
    ?>
    <form action="exercise1.php" method="post">
        <input type="number" name="num1" value="<?php echo $num1; ?>">
        <select name="operator">
            <option value="add">Add</option>
            <option value="subtract">Subtract</option>
            <option value="multiply">Multiply</option>
            <option value="divide">Divide</option>
        </select>
        <input type="number" name="num2" value="<?php echo $num2; ?>">
        <button type="submit" name="submit">Calculate</button>
        <input type="text" name="result" value="<?php echo $result; ?>">
    </form>
    </br>
    <a href="index.php">Menu</a>
</body>

</html>