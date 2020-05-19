<?php

namespace App\Queue\Processor;

use App\Application\Envelope\Processor\EnvelopeProcessor;

class CreateOfferProcessor extends EnvelopeProcessor
{
    public static function getSubscribedQueues()
    {
        return ['create-offer'];
    }
}