<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="array")
     */
    private $images = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ean13;

    /**
     * @ORM\Column(type="integer")
     */
    private $weight;

    /**
     * @ORM\Column(type="integer")
     */
    private $coffeeDeskId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shopId;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastRefresh;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function getEan13(): ?string
    {
        return $this->ean13;
    }

    public function setEan13(?string $ean13): self
    {
        $this->ean13 = $ean13;

        return $this;
    }

    public function getWeight(): ?int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getCoffeeDeskId(): ?int
    {
        return $this->coffeeDeskId;
    }

    public function setCoffeeDeskId(int $coffeeDeskId): self
    {
        $this->coffeeDeskId = $coffeeDeskId;

        return $this;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function setShopId(?int $shopId): self
    {
        $this->shopId = $shopId;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getLastRefresh(): ?\DateTimeInterface
    {
        return $this->lastRefresh;
    }

    public function setLastRefresh(?\DateTimeInterface $lastRefresh): self
    {
        $this->lastRefresh = $lastRefresh;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }
}
