<?php

namespace App\Controller;

use App\Application\Envelope\BuildOfferEnvelope;
use App\Application\WcApi\Factory\WooCommerceClientFactory;
use App\Application\WcApi\HttpClient\HttpClientException;
use App\Event\WcOfferFoundEvent;
use App\Event\WcOfferNotFoundEvent;
use App\Repository\OfferRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AController extends AbstractController
{
    private $wooCommerceClientFactory;
    private $repository;
    private $eventDispatcher;

    public function __construct(WooCommerceClientFactory $wooCommerceClientFactory, OfferRepository $repository, EventDispatcherInterface $eventDispatcher)
    {
        $this->wooCommerceClientFactory = $wooCommerceClientFactory;
        $this->repository = $repository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @Route("/a", name="a")
     */
    public function index()
    {
        $offer = $this->repository->find(6);

        $this->context->createProducer()->send(
            $this->context->createQueue('build-offer'),
            $this->context->createMessage(serialize(new BuildOfferEnvelope($offer)))
        );

        return new JsonResponse('test');
    } 
}
