<?php

namespace App\Application\Envelope;

class ProductImportProcessEnvelope implements EnvelopeInterface
{
    public $importId;

    public function __construct(int $importId)
    {
        $this->importId = $importId;
    }
}