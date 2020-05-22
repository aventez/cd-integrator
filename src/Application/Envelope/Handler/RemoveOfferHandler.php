<?php

namespace App\Application\Envelope\Handler;

use App\Application\Envelope\EnvelopeInterface;
use App\Application\Envelope\RemoveOfferEnvelope;
use App\Application\WcApi\Factory\WooCommerceClientFactory;
use App\Entity\Offer;
use App\Repository\OfferRepository;
use Psr\Log\LoggerInterface;

class RemoveOfferHandler implements EnvelopeHandlerInterface
{
    private $offerRepository;
    private $logger;
    private $wooCommerceClientFactory;

    public function __construct
    (
        OfferRepository $offerRepository,
        LoggerInterface $logger,
        WooCommerceClientFactory $wooCommerceClientFactory
    )
    {
        $this->logger = $logger;
        $this->offerRepository = $offerRepository;
        $this->wooCommerceClientFactory = $wooCommerceClientFactory;
    }

    public function handle(EnvelopeInterface $envelope): string
    {
        $client = $this->wooCommerceClientFactory->create();
        $shopId = $envelope->offerId;

        if($shopId) {
            $this->logger->alert(sprintf('Offer %d has been successfully removed. (2)', $shopId));
            $client->delete(sprintf('products/%d', $shopId));
        }

        return self::ACK;
    }

    public function supports(EnvelopeInterface $envelope): bool
    {
        return $envelope instanceof RemoveOfferEnvelope;
    }
}