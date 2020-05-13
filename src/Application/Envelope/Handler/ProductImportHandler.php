<?php

namespace App\Application\Envelope\Handler;

use App\Application\Enum\ProductImportStatusEnum;
use App\Application\Envelope\EnvelopeInterface;
use App\Application\Envelope\ProductImportProcessEnvelope;
use App\Application\Provider\ProductDataProvider;
use App\Entity\Product;
use App\Factory\ProductFactory;
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
    private $productFactory;

    public function __construct
    (
        ProductImportRepository $importRepository,
        ProductRepository $productRepository,
        LoggerInterface $logger,
        ProductDataProvider $dataProvider,
        EntityManagerInterface $em,
        ProductFactory $productFactory
    )
    {
        $this->importRepository = $importRepository;
        $this->logger = $logger;
        $this->dataProvider = $dataProvider;
        $this->productRepository = $productRepository;
        $this->em = $em;
        $this->productFactory = $productFactory;
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

        $this->productFactory->createFromProductDto($data);

        $import->setStatus(ProductImportStatusEnum::COMPLETED);
        $this->importRepository->save($import);

        return self::ACK;
    }

    public function supports(EnvelopeInterface $envelope): bool
    {
        return $envelope instanceof ProductImportProcessEnvelope;
    }
}