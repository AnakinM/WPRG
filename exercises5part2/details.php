<?php
declare(strict_types=1);
require 'src/config.php';
require 'src/db.php';
require 'src/classes/CarModel.php';
require 'src/classes/CarRepository.php';

$db = getDbConnection();
$carRepository = new CarRepository($db);
$car = $carRepository->getCarById((int) $_GET['id']);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Cars</title>
</head>

<body>
    <?php include './src/templates/navigation.html'; ?>
    <h1>Details</h1>
    <?php if (!$car): ?>
        <p>Car not found</p>
    <?php else: ?>
        <ul>
            <li>Model: <?php echo $car->getModel(); ?></li>
            <li>Brand: <?php echo $car->getBrand(); ?></li>
            <li>Price: <?php echo $car->getPrice(); ?></li>
            <li>Year: <?php echo $car->getYear(); ?></li>
            <li>Description: <?php echo $car->getDescription(); ?></li>
        </ul>
    <?php endif; ?>
</body>

</html>