<?php

namespace App\Application\Envelope;

class OfferRefreshProcessEnvelope implements EnvelopeInterface
{
    public $offerId;

    public function __construct(int $offerId)
    {
        $this->offerId = $offerId;
    }
}