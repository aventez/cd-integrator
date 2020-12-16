<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 * @UniqueEntity("coffeeDeskId")
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
     * @ORM\Column(type="integer")
     */
    private $coffeeDeskId;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shopId;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $lastRefresh;

    /**
     * @ORM\Column(type="integer")
     */
    private $stock;

    /**
     * @ORM\Column(type="integer", options={"default": -2})
     */
    private $buffer = -2;

    /**
     * @ORM\Column(type="boolean", options={"default": false})
     */
    private $syncDisabled = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Offer", mappedBy="products", cascade={"remove"})
     * @JoinColumn(onDelete="cascade")
     */
    private $offers;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $categories = [];

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceIndividualGross;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $pricePromotionalGross;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $priceRegularGross;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brand;

    public function __construct()
    {
        $this->offers = new ArrayCollection();
    }

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

    public function getBuffer(): ?int
    {
        return $this->buffer;
    }

    public function setBuffer(int $buffer): self
    {
        $this->buffer = $buffer;

        return $this;
    }

    public function getSyncDisabled(): ?bool
    {
        return $this->syncDisabled;
    }

    public function setSyncDisabled(bool $syncDisabled): self
    {
        $this->syncDisabled = $syncDisabled;

        return $this;
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return Collection|Offer[]
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addOffer(Offer $offer): self
    {
        if (!$this->offers->contains($offer)) {
            $this->offers[] = $offer;
            $offer->addProduct($this);
        }

        return $this;
    }

    public function removeOffer(Offer $offer): self
    {
        if ($this->offers->contains($offer)) {
            $this->offers->removeElement($offer);
            $offer->removeProduct($this);
        }

        return $this;
    }

    public function getCategories(): ?array
    {
        return $this->categories;
    }

    public function setCategories(?array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function getPriceIndividualGross(): ?float
    {
        return $this->priceIndividualGross;
    }

    public function getPriceDiff(): ?float
    {
        return ($this->priceRegularGross - $this->priceIndividualGross);
    }

    public function setPriceIndividualGross(?float $priceIndividualGross): self
    {
        $this->priceIndividualGross = $priceIndividualGross;

        return $this;
    }

    public function getPricePromotionalGross(): ?float
    {
        return $this->pricePromotionalGross;
    }

    public function setPricePromotionalGross(?float $pricePromotionalGross): self
    {
        $this->pricePromotionalGross = $pricePromotionalGross;

        return $this;
    }

    public function getPriceRegularGross(): ?float
    {
        return $this->priceRegularGross;
    }

    public function setPriceRegularGross(?float $priceRegularGross): self
    {
        $this->priceRegularGross = $priceRegularGross;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(?string $brand): self
    {
        $this->brand = $brand;

        return $this;
    }
}
