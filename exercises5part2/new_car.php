<?php
declare(strict_types=1);
require 'src/config.php';
require 'src/db.php';
require 'src/classes/CarModel.php';
require 'src/classes/CarRepository.php';

$db = getDbConnection();
$carRepository = new CarRepository($db);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $model = $_POST['model'];
    $brand = $_POST['brand'];
    $price = (float) $_POST['price'];
    $year = (int) $_POST['year'];
    $description = $_POST['description'];

    $car = new Car(null, $model, $brand, $price, $year, $description);
    $carRepository->newCar($car);

    header('Location: all_cars.php');
    exit;
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Cars</title>
</head>

<body>
    <?php include './src/templates/navigation.html'; ?>
    <h1>New car</h1>

    <form method="post" action="new_car.php">
        <label for="model">Model:</label>
        <input type="text" id="model" name="model" required><br>
        <label for="brand">Brand:</label>
        <input type="text" id="brand" name="brand" required><br>
        <label for="price">Price:</label>
        <input type="number" step="0.01" id="price" name="price" required><br>
        <label for="year">Year:</label>
        <input type="number" id="year" name="year" required><br>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required></textarea><br>
        <button type="submit">Add Car</button>
    </form>

</body>

</html>