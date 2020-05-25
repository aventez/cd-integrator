<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CategoryRepository")
 */
class Category
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
    private $categoryKey;

    /**
     * @ORM\Column(type="integer")
     */
    private $categoryValue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCategoryKey(): ?string
    {
        return $this->categoryKey;
    }

    public function setCategoryKey(string $categoryKey): self
    {
        $this->categoryKey = $categoryKey;

        return $this;
    }

    public function getCategoryValue(): ?int
    {
        return $this->categoryValue;
    }

    public function setCategoryValue(int $categoryValue): self
    {
        $this->categoryValue = $categoryValue;

        return $this;
    }
}
