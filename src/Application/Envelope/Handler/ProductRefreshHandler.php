<?php

namespace App\Application\Envelope\Handler;

use App\Application\Enum\ProductImportStatusEnum;
use App\Application\Envelope\EnvelopeInterface;
use App\Application\Envelope\ProductImportProcessEnvelope;
use App\Application\Envelope\ProductRefreshProcessEnvelope;
use App\Application\Provider\ProductDataProvider;
use App\Entity\Product;
use App\Repository\ProductImportRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ProductRefreshHandler implements EnvelopeHandlerInterface
{
    private $productRepository;
    private $logger;
    private $dataProvider;
    private $em;

    public function __construct
    (
        ProductRepository $productRepository,
        LoggerInterface $logger,
        ProductDataProvider $dataProvider,
        EntityManagerInterface $em
    )
    {
        $this->logger = $logger;
        $this->dataProvider = $dataProvider;
        $this->productRepository = $productRepository;
        $this->em = $em;
    }

    public function handle(EnvelopeInterface $envelope): string
    {
        $product = $this->productRepository->find($envelope->productId);
        if( !$product ) {
            $this->logger->error(sprintf('Product with id %d was not found', $envelope->productId));
            return self::REJECT;
        }

        $data = $this->dataProvider->provide($product->getCoffeeDeskId());

        // Wyrzucić do factory
        $product->setImages($data->getImages());
        $product->setEan13($data->getEan13());
        $product->setWeight($data->getWeight());
        $product->setLastRefresh(new \DateTimeImmutable());
        $product->setDescription($data->getDescription());
        $product->setName($data->getName());
        $product->setStock($data->getStock());

        $this->productRepository->save($product);

        $this->logger->info(sprintf('Product with ID %d has been synchronized with CoffeeDesk state.', $product->getId()));

        return self::ACK;
    }

    public function supports(EnvelopeInterface $envelope): bool
    {
        return $envelope instanceof ProductRefreshProcessEnvelope;
    }
}