<?php

namespace App\Queue\Processor;

use App\Application\Envelope\Processor\EnvelopeProcessor;

class RemoveOfferProcessor extends EnvelopeProcessor
{
    public static function getSubscribedQueues()
    {
        return ['offer-to-remove'];
    }
}