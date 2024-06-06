<?php
declare(strict_types=1);
/**
 * @param Car[] $cars
 */
function showAsTable(array $cars): void
{
    echo '<table border="1">';
    echo '<tr>';
    echo '<th>Model</th>';
    echo '<th>Brand</th>';
    echo '<th>Price</th>';
    echo '<th>Year</th>';
    echo '<th></th>';
    echo '</tr>';
    foreach ($cars as $car) {
        echo '<tr>';
        echo '<td>' . $car->getModel() . '</td>';
        echo '<td>' . $car->getBrand() . '</td>';
        echo '<td>' . $car->getPrice() . '</td>';
        echo '<td>' . $car->getYear() . '</td>';
        echo '<td>' . "<a href='details.php?id={$car->getId()}'>View</a>" . "</td>";
        echo '</tr>';
    }
    echo '</table>';
}