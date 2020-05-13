<?php

namespace App\Factory;

use App\Application\Model\Dto\CoffeeDeskProductDto;
use App\Entity\Product;
use App\Repository\ProductRepository;

class ProductFactory
{
    private $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createFromProductDto(CoffeeDeskProductDto $productDto): Product
    {
        $product = new Product();
        $product->setName($productDto->getName());
        $product->setDescription($productDto->getDescription());
        $product->setCoffeeDeskId($productDto->getId());
        $product->setPrice($productDto->getPrice());
        $product->setImages($productDto->getImages());
        $product->setStock($productDto->getStock());

        $this->repository->save($product);

        return $product;
    }

    public function updateProductFromDto(Product $product, CoffeeDeskProductDto $productDto): Product
    {
        $product->setPrice($productDto->getPrice());
        $product->setStock($productDto->getStock());
        $product->setLastRefresh(new \DateTimeImmutable());

        $this->repository->save($product);

        return $product;
    }
}