<?php

namespace App\Application\Provider;

use App\Application\CoffeedeskApi\ProductApiClient;
use App\Entity\ProductImport;

class ProductImportDataProvider
{
    private $endpoint;
    private $apiClient;

    public function __construct
    (
        string $endpoint,
        ProductApiClient $apiClient
    )
    {
        $this->endpoint = $endpoint;
        $this->apiClient = $apiClient;
    }

    public function provide(ProductImport $import)
    {

    }
}