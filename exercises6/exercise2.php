<?php
declare(strict_types=1);

class Product
{
    private string $name;
    private float $price;
    private int $quantity;

    public function __construct(string $name, float $price, int $quantity)
    {
        $this->name = $name;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    // Gettery i settery
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): void
    {
        $this->quantity = $quantity;
    }

    // Metoda __toString
    public function __toString(): string
    {
        return "Product: $this->name, Price: $this->price, Quantity: $this->quantity";
    }
}

class Cart
{
    private array $products;

    public function __construct()
    {
        $this->products = [];
    }

    public function addProduct(Product $product): void
    {
        $this->products[] = $product;
    }

    public function removeProduct(Product $product): void
    {
        foreach ($this->products as $key => $cartProduct) {
            if ($cartProduct->getName() === $product->getName()) {
                unset($this->products[$key]);
                $this->products = array_values($this->products); // Reindeksacja tablicy
                return;
            }
        }
    }

    public function getTotal(): float
    {
        $total = 0.0;
        foreach ($this->products as $product) {
            $total += $product->getPrice() * $product->getQuantity();
        }
        return $total;
    }

    public function __toString(): string
    {
        $output = "Products in cart:\n";
        foreach ($this->products as $product) {
            $output .= $product . "\n";
        }
        $output .= "Total price: " . $this->getTotal();
        return $output;
    }
}

$product1 = new Product("Laptop", 1500.0, 1);
$product2 = new Product("Mouse", 50.0, 2);

$cart = new Cart();
$cart->addProduct($product1);
$cart->addProduct($product2);

echo $cart;