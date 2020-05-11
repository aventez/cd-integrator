<?php

namespace App\Queue\Processor;

use App\Application\Envelope\Processor\EnvelopeProcessor;

class ProductImportProcessor extends EnvelopeProcessor
{
    public static function getSubscribedQueues()
    {
        return ['import-product-to-process'];
    }
}