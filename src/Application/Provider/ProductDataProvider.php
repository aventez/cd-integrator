<?php

namespace App\Application\Provider;

use App\Application\CoffeedeskApi\ProductApiClient;
use App\Application\CoffeedeskApi\ProductNotFoundException;
use App\Application\Model\Dto\CoffeeDeskProductDto;
use App\Entity\ProductImport;

class ProductDataProvider
{
    private $apiClient;

    public function __construct(ProductApiClient $apiClient)
    {
        $this->apiClient = $apiClient;
    }

    public function provide(int $id): ?CoffeeDeskProductDto
    {
        try {
            return $this->apiClient->getProductInfo($id);
        } catch (ProductNotFoundException $ex) {
            return NULL;
        }
    }
}