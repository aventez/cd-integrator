<?php

namespace App\EventListener;

use App\Application\Envelope\ProductImportProcessEnvelope;
use App\Event\ImportAddedEvent;
use Interop\Queue\Context;

class ProductImportEventListener
{
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public function onCreated(ImportAddedEvent $event): void
    {
        $this->context->createProducer()->send(
            $this->context->createQueue('import-product-to-process'),
            $this->context->createMessage(serialize(new ProductImportProcessEnvelope($event->getImport()->getId())))
        );
    }
}