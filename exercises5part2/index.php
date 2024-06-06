<?php
declare(strict_types=1);
require 'src/config.php';
require 'src/db.php';
require 'src/classes/CarModel.php';
require 'src/classes/CarRepository.php';
include 'src/templates/table.php';

$db = getDbConnection();
$carRepository = new CarRepository($db);
$cars = $carRepository->getTopCheapestCars(5);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Cars</title>
</head>

<body>
    <?php include 'src/templates/navigation.html'; ?>
    <?php showAsTable($cars); ?>

</body>

</html>