<?php

namespace App\Application\Model\Dto;

class CoffeeDeskProductDto
{
    private $id;
    private $name;
    private $description;
    private $images;
    private $stock;
    private $priceIndividualGross;
    private $pricePromotionalGross;
    private $priceRegularGross;
    private $brand;

    public function __construct
    (
        int $id,
        string $name,
        string $description,
        ?array $images,
        ?int $stock,
        ?float $priceIndividualGross,
        ?float $pricePromotionalGross,
        ?float $priceRegularGross,
        ?string $brand
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->images = $images;
        $this->stock = $stock;
        $this->priceIndividualGross = $priceIndividualGross;
        $this->pricePromotionalGross = $pricePromotionalGross;
        $this->priceRegularGross = $priceRegularGross;
        $this->brand = $brand;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getImages(): array
    {
        return $this->images;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function getPriceIndividualGross(): float
    {
        return $this->priceIndividualGross;
    }

    public function getPricePromotionalGross(): float
    {
        return $this->pricePromotionalGross;
    }

    public function getPriceRegularGross(): float
    {
        return $this->priceRegularGross;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public static function constructFromData(array $record): ?self
    {
        return new self(
            $record['id'],
            $record['name'],
            $record['description'],
            $record['images'],
            $record['availableStock'],
            $record['priceIndividualGross'],
            $record['pricePromotionalGross'],
            $record['priceRegularGross'],
            $record['brand']
        );
    }
}