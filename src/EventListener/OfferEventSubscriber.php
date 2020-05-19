<?php

namespace App\EventListener;

use App\Application\Envelope\BuildOfferEnvelope;
use App\Application\Envelope\ProductImportProcessEnvelope;
use App\Event\ImportAddedEvent;
use App\Event\WcOfferFoundEvent;
use App\Event\WcOfferNotFoundEvent;
use Interop\Queue\Context;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class OfferEventSubscriber implements EventSubscriberInterface
{
    private $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    public static function getSubscribedEvents()
    {
        return [
            WcOfferFoundEvent::class => 'onOfferFound',
            WcOfferNotFoundEvent::class => 'onOfferNotFound'
        ];
    }

    public function onOfferFound(WcOfferFoundEvent $event)
    {
        $this->context->createProducer()->send(
            $this->context->createQueue('build-offer'),
            $this->context->createMessage(serialize(new BuildOfferEnvelope($event->getOffer())))
        );
    }

    public function onOfferNotFound(WcOfferNotFoundEvent $event)
    {
        /*$this->context->createProducer()->send(
            $this->context->createQueue('create-offer'),
            $this->context->createMessage(serialize(new CreateOfferEnvelope($event->getOffer())))
        );*/
    }
}