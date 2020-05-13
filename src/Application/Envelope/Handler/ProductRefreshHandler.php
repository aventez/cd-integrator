<?php

namespace App\Application\Envelope\Handler;

use App\Application\Enum\ProductImportStatusEnum;
use App\Application\Envelope\EnvelopeInterface;
use App\Application\Envelope\ProductImportProcessEnvelope;
use App\Application\Envelope\ProductRefreshProcessEnvelope;
use App\Application\Provider\ProductDataProvider;
use App\Entity\Product;
use App\Factory\ProductFactory;
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
    private $productFactory;

    public function __construct
    (
        ProductRepository $productRepository,
        LoggerInterface $logger,
        ProductDataProvider $dataProvider,
        EntityManagerInterface $em,
        ProductFactory $productFactory
    )
    {
        $this->logger = $logger;
        $this->dataProvider = $dataProvider;
        $this->productRepository = $productRepository;
        $this->em = $em;
        $this->productFactory = $productFactory;
    }

    public function handle(EnvelopeInterface $envelope): string
    {
        $product = $this->productRepository->find($envelope->productId);
        if( !$product ) {
            $this->logger->error(sprintf('Product with id %d was not found', $envelope->productId));
            return self::REJECT;
        }

        $data = $this->dataProvider->provide($product->getCoffeeDeskId());
        if( !$data ) {
            $this->logger->error(sprintf('Product with cd-id %d was not found in CoffeeDesk. Deleting product.', $product->getCoffeeDeskId()));
            $this->productRepository->delete($product);

            return self::REJECT;
        }

        $this->productFactory->updateProductFromDto($product, $data);
        $this->logger->info(sprintf('Product with ID %d has been synchronized with CoffeeDesk state.', $product->getId()));

        return self::ACK;
    }

    public function supports(EnvelopeInterface $envelope): bool
    {
        return $envelope instanceof ProductRefreshProcessEnvelope;
    }
}