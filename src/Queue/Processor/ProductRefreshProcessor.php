<?php

namespace App\Queue\Processor;

use App\Application\Envelope\Processor\EnvelopeProcessor;

class ProductRefreshProcessor extends EnvelopeProcessor
{
    public static function getSubscribedQueues()
    {
        return ['refresh-product-to-process'];
    }
}