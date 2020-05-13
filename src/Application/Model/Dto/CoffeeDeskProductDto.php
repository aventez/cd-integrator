<?php

namespace App\Application\Model\Dto;

class CoffeeDeskProductDto
{
    private $id;
    private $name;
    private $description;
    private $images;
    private $stock;
    private $price;

    public function __construct
    (
        int $id,
        string $name,
        string $description,
        ?array $images,
        ?int $stock,
        float $price
    )
    {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->images = $images;
        $this->stock = $stock;
        $this->price = $price;
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

    public function getPrice(): float
    {
        return $this->price;
    }

    public static function constructFromData(array $record): ?self
    {
        return new self(
            $record['id'],
            $record['name'],
            $record['description'],
            $record['images'],
            $record['availableStock'],
            $record['pricePromotionalGross'] ? $record['pricePromotionalGross'] : $record['priceRegularGross']
        );
    }
}