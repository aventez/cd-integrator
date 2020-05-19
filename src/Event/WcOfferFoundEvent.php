<?php

namespace App\Event;

use App\Entity\Offer;
use Symfony\Contracts\EventDispatcher\Event;

class WcOfferFoundEvent extends Event
{
    private $offer;

    public function __construct(Offer $offer)
    {
        $this->offer = $offer;
    }

    public function getOffer(): Offer
    {
        return $this->offer;
    }
}