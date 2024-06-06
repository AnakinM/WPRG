<?php
declare(strict_types=1);
class CarRepository
{
    private mysqli $db;

    public function __construct(mysqli $db)
    {
        $this->db = $db;
    }

    public function __destruct()
    {
        if ($this->db) {
            mysqli_close($this->db);
        }
    }

    /**
     * @return Car[]
     */
    public function getCars(?string $sortedBy = null): array
    {
        $query = 'SELECT * FROM cars';
        if ($sortedBy) {
            if (!in_array($sortedBy, ['model', 'brand', 'price', 'year']))
                throw new InvalidArgumentException('Invalid sorting parameter');
            $query .= ' ORDER BY ' . $sortedBy;
        }
        $result = mysqli_query($this->db, $query);
        $cars = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $car = new Car((int) $row['id'], $row['model'], $row['brand'], (float) $row['price'], (int) $row['year'], $row['description']);
            $cars[] = $car;
        }
        return $cars;
    }

    /**
     * @return Car[]
     */
    public function getTopCheapestCars(int $limit): array
    {
        $query = 'SELECT * FROM cars ORDER BY price ASC LIMIT ?';
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, 'i', $limit);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $cars = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $car = new Car((int) $row['id'], $row['model'], $row['brand'], (float) $row['price'], (int) $row['year'], $row['description']);
            $cars[] = $car;
        }
        return $cars;
    }

    public function newCar(Car $car): Car
    {
        $model = $car->getModel();
        $brand = $car->getBrand();
        $price = $car->getPrice();
        $year = $car->getYear();
        $description = $car->getDescription();
        $query = 'INSERT INTO cars (model, brand, price, year, description) VALUES (?, ?, ?, ?, ?)';
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, 'ssdis', $model, $brand, $price, $year, $description);
        mysqli_stmt_execute($stmt);
        $carId = mysqli_insert_id($this->db);
        $car->setId($carId);
        return $car;
    }

    public function getCarById(int $id): ?Car
    {
        $query = 'SELECT * FROM cars WHERE id = ?';
        $stmt = mysqli_prepare($this->db, $query);
        mysqli_stmt_bind_param($stmt, 'i', $id);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            return null;
        }
        return new Car((int) $row['id'], $row['model'], $row['brand'], (float) $row['price'], (int) $row['year'], $row['description']);
    }

}
