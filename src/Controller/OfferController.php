<?php

namespace App\Controller;

use App\Application\Envelope\OfferRefreshProcessEnvelope;
use App\Repository\OfferRepository;
use Interop\Queue\Context;

class OfferController extends MainController
{
    private $repository;
    private $context;

    public function __construct(OfferRepository $repository, Context $context)
    {
        $this->context = $context;
        $this->repository = $repository;
    }

    public function refreshAction()
    {
        $queue = $this->context->createQueue('refresh-offer-to-process');

        $offers = $this->repository->findAll();
        foreach($offers as $offer) {
            $message = $this->context->createMessage(serialize(new OfferRefreshProcessEnvelope($offer->getId())));
            $this->context->createProducer()->send($queue, $message);
        }

        return $this->redirectToReferrer();
    }
}