<?php

namespace App\Controller;

use App\Application\Envelope\ProductRefreshProcessEnvelope;
use App\Repository\ProductRepository;
use Interop\Queue\Context;

class ProductController extends MainController
{
    private $repository;
    private $context;

    public function __construct(ProductRepository $repository, Context $context)
    {
        $this->context = $context;
        $this->repository = $repository;
    }

    public function refreshAction()
    {
        $queue = $this->context->createQueue('refresh-product-to-process');

        $products = $this->repository->findAll();
        foreach($products as $product) {
            if($product->getSyncDisabled() != true) {
                $message = $this->context->createMessage(serialize(new ProductRefreshProcessEnvelope($product->getId())));
                $this->context->createProducer()->send($queue, $message);
            }
        }

        return $this->redirectToReferrer();
    }
}