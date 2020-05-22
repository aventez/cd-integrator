<?php

namespace App\EventListener;

use App\Application\Envelope\RemoveOfferEnvelope;
use App\Entity\Offer;
use App\Entity\Product;
use App\Repository\OfferRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Interop\Queue\Context;
use Psr\Log\LoggerInterface;

class OfferEventListener
{
    private $context;
    private $offerRepository;
    private $logger;

    public function __construct(Context $context, OfferRepository $offerRepository, LoggerInterface $logger)
    {
        $this->context = $context;
        $this->offerRepository = $offerRepository;
        $this->logger = $logger;
    }

    public function onRemove(Offer $offer, LifecycleEventArgs $event): void
    {
        $this->logger->alert(
            sprintf('Offer %s (%d) has been removed. Removing from WooCommerce', $offer->getName(), $offer->getId())
        );

        if($offer->getShopId()) {
            $this->context->createProducer()->send(
                $this->context->createQueue('offer-to-remove'),
                $this->context->createMessage(serialize(new RemoveOfferEnvelope($offer->getShopId())))
            );
        }
    }
}