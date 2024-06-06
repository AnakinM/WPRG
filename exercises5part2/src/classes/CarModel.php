<?php
declare(strict_types=1);
class Car
{
    private ?int $id;
    private string $model;
    private string $brand;
    private float $price;
    private int $year;
    private string $description;

    public function __construct(?int $id, string $model, string $brand, float $price, int $year, string $description)
    {
        $this->id = $id;
        $this->model = $model;
        $this->brand = $brand;
        $this->price = $price;
        $this->year = $year;
        $this->description = $description;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

}
