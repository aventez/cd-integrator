<?php

namespace App\Application\Envelope;

class ProductRefreshProcessEnvelope implements EnvelopeInterface
{
    public $productId;

    public function __construct(int $productId)
    {
        $this->productId = $productId;
    }
}