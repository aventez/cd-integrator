<?php

namespace App\EventListener;

use App\Entity\Product;
use App\Repository\OfferRepository;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Interop\Queue\Context;
use Psr\Log\LoggerInterface;

class ProductEventListener
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

    public function onRemove(Product $product, LifecycleEventArgs $event): void
    {
        $this->logger->alert(
            sprintf('Product %s (%d) has been removed. Removing associated offers', $product->getName(), $product->getId())
        );
    }
}