<?php
declare(strict_types=1);

class NoweAuto
{
    private string $model;
    private float|int $cenaEuro;
    private float $kursEuroPLN;

    public function __construct(string $model, float|int $cenaEuro, float $kursEuroPLN)
    {
        $this->model = $model;
        $this->cenaEuro = $cenaEuro;
        $this->kursEuroPLN = $kursEuroPLN;
    }

    public function obliczCene(): float
    {
        return $this->cenaEuro * $this->kursEuroPLN;
    }

    // Gettery
    public function getModel(): string
    {
        return $this->model;
    }

    public function getCenaEuro(): float|int
    {
        return $this->cenaEuro;
    }

    public function getKursEuroPLN(): float
    {
        return $this->kursEuroPLN;
    }
}

class AutoZDodatkami extends NoweAuto
{
    private float|int $alarm;
    private float|int $radio;
    private float|int $klimatyzacja;

    public function __construct(string $model, float|int $cenaEuro, float $kursEuroPLN, float|int $alarm, float|int $radio, float|int $klimatyzacja)
    {
        parent::__construct($model, $cenaEuro, $kursEuroPLN);
        $this->alarm = $alarm;
        $this->radio = $radio;
        $this->klimatyzacja = $klimatyzacja;
    }

    public function obliczCene(): float
    {
        $cenaBazowaPLN = parent::obliczCene();
        $cenaDodatkow = $this->alarm + $this->radio + $this->klimatyzacja;
        return $cenaBazowaPLN + $cenaDodatkow;
    }

    // Gettery
    public function getAlarm(): float|int
    {
        return $this->alarm;
    }

    public function getRadio(): float|int
    {
        return $this->radio;
    }

    public function getKlimatyzacja(): float|int
    {
        return $this->klimatyzacja;
    }
}

class Ubezpieczenie extends AutoZDodatkami
{
    private float $procentUbezpieczenia;
    private int $liczbaLat;

    public function __construct(string $model, float|int $cenaEuro, float $kursEuroPLN, float|int $alarm, float|int $radio, float|int $klimatyzacja, float $procentUbezpieczenia, int $liczbaLat)
    {
        parent::__construct($model, $cenaEuro, $kursEuroPLN, $alarm, $radio, $klimatyzacja);
        $this->procentUbezpieczenia = $procentUbezpieczenia;
        $this->liczbaLat = $liczbaLat;
    }

    public function obliczCene(): float
    {
        $cenaAutaZDodatkami = parent::obliczCene();
        $wartoscUbezpieczenia = $this->procentUbezpieczenia * $cenaAutaZDodatkami * ((100 - $this->liczbaLat) / 100);
        return $cenaAutaZDodatkami + $wartoscUbezpieczenia;
    }

    // Gettery
    public function getProcentUbezpieczenia(): float
    {
        return $this->procentUbezpieczenia;
    }

    public function getLiczbaLat(): int
    {
        return $this->liczbaLat;
    }
}


$auto = new Ubezpieczenie('Audi A4', 30000, 4.5, 1000, 500, 2000, 0.05, 2);
echo "Cena auta z ubezpieczeniem w PLN: " . $auto->obliczCene();
