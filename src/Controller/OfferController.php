<?php

namespace App\Controller;

use App\Application\Envelope\OfferRefreshProcessEnvelope;
use App\Entity\Offer;
use App\Repository\OfferRepository;
use App\Repository\ProductRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Interop\Queue\Context;

class OfferController extends MainController
{
    private $repository;
    private $context;
    private $productRepository;

    public function __construct(OfferRepository $repository, Context $context, ProductRepository $productRepository)
    {
        $this->context = $context;
        $this->repository = $repository;
        $this->productRepository = $productRepository;
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

    protected function createEntityForm($entity, array $entityProperties, $view)
    {
        if(!$entity instanceof Offer) throw new \Exception(sprintf('The "%s" method must return a Offer, "%s" given.', 'createEntityForm', get_class($entity)));

        if(isset($entityProperties['change'])) {
            if(isset($entityProperties['price'])) $entity->setPrice($entityProperties['price']);
            if(isset($entityProperties['name'])) $entity->setName($entityProperties['name']);
            if(isset($entityProperties['product'])) $entity->addProduct($entityProperties['product']);
            if(isset($entityProperties['description'])) {
                $entity->setDescription($entityProperties['description']);
                $entity->setShortDescription($entityProperties['description']);
            }
        }

        $formBuilder = $this->executeDynamicMethod('create<EntityName>EntityFormBuilder', [$entity, $view]);
        return $formBuilder->getForm();
    }

    public function newAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_NEW);

        $id = $this->request->query->get('product');
        $product = null;
        if($id) $product = $this->productRepository->find($id);

        $entity = $this->executeDynamicMethod('createNew<EntityName>Entity');

        $easyadmin = $this->request->attributes->get('easyadmin');
        $easyadmin['item'] = $entity;
        $this->request->attributes->set('easyadmin', $easyadmin);

        $fields = (array) null;
        if($product) {
            $fields['price'] = $product->getPrice();
            $fields['name'] = $product->getName();
            $fields['description'] = $product->getDescription();
            $fields['product'] = $product;
            $fields['change'] = true;
        }

        $newForm = $this->executeDynamicMethod('create<EntityName>NewForm', [$entity, $fields]);

        $newForm->handleRequest($this->request);
        if ($newForm->isSubmitted() && $newForm->isValid()) {
            $this->dispatch(EasyAdminEvents::PRE_PERSIST, ['entity' => $entity]);
            $this->executeDynamicMethod('persist<EntityName>Entity', [$entity, $newForm]);
            $this->dispatch(EasyAdminEvents::POST_PERSIST, ['entity' => $entity]);

            return $this->redirectToReferrer();
        }

        $this->dispatch(EasyAdminEvents::POST_NEW, [
            'entity_fields' => $fields,
            'form' => $newForm,
            'entity' => $entity,
        ]);

        $parameters = [
            'form' => $newForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['new', $this->entity['templates']['new'], $parameters]);
    }
}