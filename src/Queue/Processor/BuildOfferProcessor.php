<?php

namespace App\Queue\Processor;

use App\Application\Envelope\Processor\EnvelopeProcessor;

class BuildOfferProcessor extends EnvelopeProcessor
{
    public static function getSubscribedQueues()
    {
        return ['build-offer'];
    }
}