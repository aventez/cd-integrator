<?php

namespace App\Application\Envelope\Handler;

use App\Application\Enum\ProductImportStatusEnum;
use App\Application\Envelope\BuildOfferEnvelope;
use App\Application\Envelope\CreateOfferEnvelope;
use App\Application\Envelope\EnvelopeInterface;
use App\Application\Envelope\OfferRefreshProcessEnvelope;
use App\Application\Envelope\ProductImportProcessEnvelope;
use App\Application\Envelope\ProductRefreshProcessEnvelope;
use App\Application\Provider\ProductDataProvider;
use App\Application\WcApi\Factory\WooCommerceClientFactory;
use App\Application\WcApi\HttpClient\HttpClientException;
use App\Entity\Offer;
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

class CreateOfferHandler implements EnvelopeHandlerInterface
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

        $offer = $this->offerRepository->find($envelope->offerId);
        if( !$offer or (!$offer instanceof Offer) ) {
            $this->logger->critical('Offer data not found.');
            return self::REJECT;
        }

        $stockQuantity = null;
        $imagesArray = (array) null;
        $ids = (array) null;
        foreach($products = $offer->getProducts() as $product) {
            $newStock = $product->getStock() + $product->getBuffer();
            if($newStock < 0) $newStock = 0;

            if($stockQuantity === null) $stockQuantity = $newStock;
            else {
                if($stockQuantity > $newStock) {
                    $stockQuantity = $newStock;
                }
            }

            foreach($images = $product->getImages() as $image) {
                $imagesArray[]['src'] = $image;
            }

            $ids[] = [
                'key' => 'id',
                'value' => $product->getCoffeeDeskId(),
            ];
        }

        $groupedIds = (array) null;
        foreach($offer->getGroupedOffers() as $grouped) {
            $groupedIds[] = $grouped->getShopId();
        }

        $response  = $client->post('products', [
            'name' => $offer->getName(),
            'regular_price' => (string) $offer->getPrice(),
            'description' => $offer->getDescription(),
            'short_description' => $offer->getShortDescription(),
            'images' => $imagesArray,
            'manage_stock' => true,
            'stock_quantity' => (int) $stockQuantity,
            'meta_data' => $ids,
            'grouped_products' => $groupedIds
        ]);

        $offer->setShopId($response->id);
        $this->em->flush();

        return self::ACK;
    }

    public function supports(EnvelopeInterface $envelope): bool
    {
        return $envelope instanceof CreateOfferEnvelope;
    }
}