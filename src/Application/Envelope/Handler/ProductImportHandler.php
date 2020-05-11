<?php

namespace App\Application\Envelope\Handler;

use App\Application\Enum\ProductImportStatusEnum;
use App\Application\Envelope\EnvelopeInterface;
use App\Application\Envelope\ProductImportProcessEnvelope;
use App\Application\Provider\ProductDataProvider;
use App\Entity\Product;
use App\Repository\ProductImportRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;

class ProductImportHandler implements EnvelopeHandlerInterface
{
    private $productRepository;
    private $importRepository;
    private $logger;
    private $dataProvider;
    private $em;

    public function __construct
    (
        ProductImportRepository $importRepository,
        ProductRepository $productRepository,
        LoggerInterface $logger,
        ProductDataProvider $dataProvider,
        EntityManagerInterface $em
    )
    {
        $this->importRepository = $importRepository;
        $this->logger = $logger;
        $this->dataProvider = $dataProvider;
        $this->productRepository = $productRepository;
        $this->em = $em;
    }

    public function handle(EnvelopeInterface $envelope): string
    {
        $import = $this->importRepository->find($envelope->importId);
        if( !$import ) {
            $this->logger->error(sprintf('Product import with id %d was not found', $envelope->importId));
            return self::REJECT;
        }

        $import->setStatus(ProductImportStatusEnum::PROCESSING);
        $this->importRepository->save($import);

        $data = $this->dataProvider->provide($import->getOriginalId());

        if( !$data ) {
            $import->setStatus(ProductImportStatusEnum::REJECTED);
            $this->importRepository->save($import);

            $this->logger->error(sprintf('Product with id %d was not found.', $import->getOriginalId()));
            return self::REJECT;
        }

        $product = new Product();
        $product->setName($data->getName());
        $product->setDescription($data->getDescription());
        $product->setCoffeeDeskId($data->getId());
        $product->setEan13($data->getEan13());
        $product->setPrice($data->getPrice());
        $product->setWeight($data->getWeight());
        $product->setImages($data->getImages());
        $product->setStock($data->getStock());
        $this->productRepository->save($product);

        $import->setStatus(ProductImportStatusEnum::COMPLETED);
        $this->importRepository->save($import);

        return self::ACK;
    }

    public function supports(EnvelopeInterface $envelope): bool
    {
        return $envelope instanceof ProductImportProcessEnvelope;
    }
}