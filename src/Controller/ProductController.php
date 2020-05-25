<?php

namespace App\Controller;

use App\Application\Envelope\ProductRefreshProcessEnvelope;
use App\Entity\Offer;
use App\Entity\Product;
use App\Repository\ProductRepository;
use Interop\Queue\Context;
use Symfony\Component\Form\Form;

class ProductController extends MainController
{
    private $repository;
    private $context;

    public function __construct(ProductRepository $repository, Context $context)
    {
        $this->context = $context;
        $this->repository = $repository;
    }

    public function generateOfferAction()
    {
        $id = $this->request->query->get('id');

        return $this->redirectToRoute('easyadmin', [
            'entity' => 'Offer',
            'action' => 'new',
            'product' => $id
        ]);
    }

    public function createOffersBatchAction(array $ids)
    {
        foreach ($ids as $id) {
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);

            $offer = new Offer();
            $offer->setPrice($product->getPriceRegularGross());
            $offer->setDescription($product->getDescription());
            $offer->setShortDescription($product->getDescription());
            $offer->setName($product->getName());
            $offer->addProduct($product);

            $this->em->persist($offer);
        }

        $this->em->flush();
    }

    public function disableSyncBatchAction(array $ids)
    {
        foreach ($ids as $id) {
            $product = $this->getDoctrine()->getRepository(Product::class)->find($id);
            $product->setSyncDisabled(!$product->getSyncDisabled());
        }

        $this->em->flush();
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