<?php

namespace App\Application\Envelope;

use App\Entity\Offer;

class RemoveOfferEnvelope implements EnvelopeInterface
{
    public $offerId;

    public function __construct(int $offerId)
    {
        $this->offerId = $offerId;
    }
}