<?php

namespace App\Application\Envelope\Handler;

use App\Application\Enum\ProductImportStatusEnum;
use App\Application\Envelope\EnvelopeInterface;
use App\Application\Envelope\ProductImportProcessEnvelope;
use App\Application\Provider\ProductImportDataProvider;
use App\Repository\ProductImportRepository;
use Psr\Log\LoggerInterface;

class ProductImportHandler implements EnvelopeHandlerInterface
{
    private $importRepository;
    private $logger;
    private $dataProvider;

    public function __construct
    (
        ProductImportRepository $importRepository,
        LoggerInterface $logger,
        ProductImportDataProvider $dataProvider
    )
    {
        $this->importRepository = $importRepository;
        $this->logger = $logger;
        $this->dataProvider = $dataProvider;
    }

    public function handle(EnvelopeInterface $envelope): string
    {
        $import = $this->importRepository->findOneBy(['id' => $envelope->importId]);
        if( !$import ) {
            $this->logger->error(sprintf('Product import with id %d was not found', $envelope->importId));
            return self::REJECT;
        }

        $import->setStatus(ProductImportStatusEnum::PROCESSING);
        $this->importRepository->save($import);

        $import = $this->importRepository->findOneBy(['id' => $envelope->importId]);
        $import->setStatus(ProductImportStatusEnum::PROCESSING);
        $this->importRepository->save($import);

        return self::ACK;
    }

    public function supports(EnvelopeInterface $envelope): bool
    {
        return $envelope instanceof ProductImportProcessEnvelope;
    }
}