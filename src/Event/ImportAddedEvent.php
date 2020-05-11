<?php

namespace App\Event;

use App\Entity\ProductImport;
use Symfony\Contracts\EventDispatcher\Event;

class ImportAddedEvent extends Event
{
    public const NAME = 'app.import.created';
    private $import;

    public function __construct(ProductImport $import)
    {
        $this->import = $import;
    }

    public function getImport()
    {
        return $this->import;
    }
}