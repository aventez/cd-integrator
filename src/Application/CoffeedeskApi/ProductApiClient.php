<?php

namespace App\Application\CoffeedeskApi;

use GuzzleHttp\Client;

class ProductApiClient extends Client
{
    private $apiKey;

    public function __construct(string $endpoint, string $apiKey)
    {
        parent::__construct([
            'base_uri' => $endpoint,
            'timeout' => 30
        ]);

        $this->apiKey = $apiKey;
    }

    public function getProductInfo(int $id)
    {
        
    }
}