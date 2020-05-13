<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductImportRepository")
 * @UniqueEntity("originalId")
 */
class ProductImport
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $originalId;

    /**
     * @ORM\Column(type="string", options={"default": "new"})
     */
    private $status = 'new';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOriginalId(): ?int
    {
        return $this->originalId;
    }

    public function setOriginalId(int $originalId): self
    {
        $this->originalId = $originalId;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }
}
