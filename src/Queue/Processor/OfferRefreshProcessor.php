<?php

namespace App\Queue\Processor;

use App\Application\Envelope\Processor\EnvelopeProcessor;

class OfferRefreshProcessor extends EnvelopeProcessor
{
    public static function getSubscribedQueues()
    {
        return ['refresh-offer-to-process'];
    }
}