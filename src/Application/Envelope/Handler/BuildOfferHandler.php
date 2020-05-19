<?php

namespace App\Application\Envelope\Handler;

use App\Application\Enum\ProductImportStatusEnum;
use App\Application\Envelope\BuildOfferEnvelope;
use App\Application\Envelope\EnvelopeInterface;
use App\Application\Envelope\OfferRefreshProcessEnvelope;
use App\Application\Envelope\ProductImportProcessEnvelope;
use App\Application\Envelope\ProductRefreshProcessEnvelope;
use App\Application\Provider\ProductDataProvider;
use App\Application\WcApi\Factory\WooCommerceClientFactory;
use App\Application\WcApi\HttpClient\HttpClientException;
use App\Entity\Product;
use App\Event\WcOfferFoundEvent;
use App\Event\WcOfferNotFoundEvent;
use App\Factory\ProductFactory;
use App\Repository\OfferRepository;
use App\Repository\ProductImportRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class BuildOfferHandler implements EnvelopeHandlerInterface
{
    private $offerRepository;
    private $logger;
    private $em;
    private $wooCommerceClientFactory;
    private $eventDispatcher;

    public function __construct
    (
        OfferRepository $offerRepository,
        LoggerInterface $logger,
        EntityManagerInterface $em,
        WooCommerceClientFactory $wooCommerceClientFactory,
        EventDispatcherInterface $eventDispatcher
    )
    {
        $this->logger = $logger;
        $this->offerRepository = $offerRepository;
        $this->em = $em;
        $this->wooCommerceClientFactory = $wooCommerceClientFactory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function handle(EnvelopeInterface $envelope): string
    {
        $client = $this->wooCommerceClientFactory->create();

        $this->logger->critical('test 123');

        return self::ACK;
    }

    public function supports(EnvelopeInterface $envelope): bool
    {
        return $envelope instanceof BuildOfferEnvelope;
    }
}