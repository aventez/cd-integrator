<?php

namespace App\Application\Envelope\Handler;

use App\Application\Enum\ProductImportStatusEnum;
use App\Application\Envelope\EnvelopeInterface;
use App\Application\Envelope\OfferRefreshProcessEnvelope;
use App\Application\Envelope\ProductImportProcessEnvelope;
use App\Application\Envelope\ProductRefreshProcessEnvelope;
use App\Application\Provider\ProductDataProvider;
use App\Application\WcApi\Factory\WooCommerceClientFactory;
use App\Entity\Product;
use App\Factory\ProductFactory;
use App\Repository\OfferRepository;
use App\Repository\ProductImportRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class OfferRefreshHandler implements EnvelopeHandlerInterface
{
    private $offerRepository;
    private $logger;
    private $em;
    private $wooCommerceClientFactory;

    public function __construct
    (
        OfferRepository $offerRepository,
        LoggerInterface $logger,
        EntityManagerInterface $em,
        WooCommerceClientFactory $wooCommerceClientFactory
    )
    {
        $this->logger = $logger;
        $this->offerRepository = $offerRepository;
        $this->em = $em;
        $this->wooCommerceClientFactory = $wooCommerceClientFactory;
    }

    public function handle(EnvelopeInterface $envelope): string
    {
        $client = $this->wooCommerceClientFactory->create();
        $res = $client->get('/orders');
        //$this->logger->critical('test log');

        return self::ACK;
    }

    public function supports(EnvelopeInterface $envelope): bool
    {
        return $envelope instanceof OfferRefreshProcessEnvelope;
    }
}