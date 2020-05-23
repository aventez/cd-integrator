<?php

namespace App\Application\CoffeedeskApi;

use App\Application\Model\Dto\CoffeeDeskProductDto;
use GuzzleHttp\Client;

class ProductApiClient extends Client
{
    private $apiKey;

    public function __construct(string $endpoint, string $apiKey)
    {
        parent::__construct([
            'base_uri' => $endpoint,
            'timeout' => 30,
            'http_errors' => false,
            'headers' => [
                'apikey' => $apiKey
            ]
        ]);

        $this->apiKey = $apiKey;
    }

    public function getProductInfo(int $id): ?CoffeeDeskProductDto
    {
        $response = $this->get(sprintf('products/%d', $id));

        if($response->getStatusCode() !== 200) {
            throw new ProductNotFoundException(sprintf('Product with id %d was not found.', $id));
        }

        $response = json_decode($response->getBody()->getContents(), true);

        if(isset($response['id'])) return CoffeeDeskProductDto::constructFromData($response);
        return NULL;
    }
}