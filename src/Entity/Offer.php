<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OfferRepository")
 */
class Offer
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\Column(type="text")
     */
    private $shortDescription;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="offers")
     */
    private $products;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Offer", inversedBy="groupedOffers")
     */
    private $groupedOffers;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $shopId;

    public function __construct()
    {
        $this->products = new ArrayCollection();
        $this->groupedOffers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

    public function __toString()
    {
        return sprintf('%s (ID: %d)',
            $this->name,
            $this->id
        );
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
        }

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getGroupedOffers(): Collection
    {
        return $this->groupedOffers;
    }

    public function addGroupedOffer(self $groupedOffer): self
    {
        if (!$this->groupedOffers->contains($groupedOffer)) {
            $this->groupedOffers[] = $groupedOffer;
        }

        return $this;
    }

    public function removeGroupedOffer(self $groupedOffer): self
    {
        if ($this->groupedOffers->contains($groupedOffer)) {
            $this->groupedOffers->removeElement($groupedOffer);
        }

        return $this;
    }

    public function getShopId(): ?int
    {
        return $this->shopId;
    }

    public function setShopId(int $shopId): self
    {
        $this->shopId = $shopId;

        return $this;
    }
}
