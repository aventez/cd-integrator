<?php

namespace App\Application\Envelope;

use App\Entity\Offer;

class BuildOfferEnvelope implements EnvelopeInterface
{
    public $offer;

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }
}