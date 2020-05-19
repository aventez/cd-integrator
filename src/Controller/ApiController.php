<?php

namespace App\Controller;

use App\Application\Envelope\BuildOfferEnvelope;
use App\Application\Envelope\OfferRefreshProcessEnvelope;
use App\Application\Envelope\ProductRefreshProcessEnvelope;
use App\Application\WcApi\Factory\WooCommerceClientFactory;
use App\Application\WcApi\HttpClient\HttpClientException;
use App\Event\WcOfferFoundEvent;
use App\Event\WcOfferNotFoundEvent;
use App\Repository\OfferRepository;
use App\Repository\ProductRepository;
use Interop\Queue\Context;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    private $offerRepository;
    private $productRepository;
    private $context;

    public function __construct(OfferRepository $offerRepository, ProductRepository $productRepository, Context $context)
    {
        $this->offerRepository = $offerRepository;
        $this->productRepository = $productRepository;
        $this->context = $context;
    }

    /**
     * @Route("/refresh-offers", name="app_refresh_offers")
     */
    public function refreshOffers()
    {
        $queue = $this->context->createQueue('refresh-offer-to-process');

        $offers = $this->offerRepository->findAll();
        foreach($offers as $offer) {
            $message = $this->context->createMessage(serialize(new OfferRefreshProcessEnvelope($offer->getId())));
            $this->context->createProducer()->send($queue, $message);
        }

        return new JsonResponse(['result' => 'OK']);
    }

    /**
     * @Route("/refresh-products", name="app_refresh_products")
     */
    public function refreshProducts()
    {
        $queue = $this->context->createQueue('refresh-product-to-process');

        foreach($products = $this->productRepository->findAll() as $product) {
            if($product->getSyncDisabled() != true) {
                $message = $this->context->createMessage(serialize(new ProductRefreshProcessEnvelope($product->getId())));
                $this->context->createProducer()->send($queue, $message);
            }
        }

        return new JsonResponse(['result' => 'OK']);
    }
}