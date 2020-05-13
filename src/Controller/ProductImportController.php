<?php

namespace App\Controller;

use App\Event\ImportAddedEvent;
use App\Repository\ProductImportRepository;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\Form;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductImportController extends MainController
{
    private $translator;
    private $repository;
    private $dispatcher;

    public function __construct
    (
        TranslatorInterface $translator,
        EventDispatcherInterface $dispatcher,
        ProductImportRepository $repository
    )
    {
        $this->translator = $translator;
        $this->dispatcher = $dispatcher;
        $this->repository = $repository;
    }

    public function newAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_NEW);

        $entity = $this->executeDynamicMethod('createNew<EntityName>Entity');

        $easyAdmin = $this->request->attributes->get('easyadmin');
        $easyAdmin['item'] = $entity;
        $this->request->attributes->set('easyadmin', $easyAdmin);

        $fields = $this->entity['new']['fields'];

        /** @var Form $newForm */
        $newForm = $this->executeDynamicMethod('create<EntityName>NewForm', [$entity, $fields]);

        $newForm->handleRequest($this->request);
        if ($newForm->isSubmitted() && $newForm->isValid()) {
            $this->dispatch(EasyAdminEvents::PRE_PERSIST, ['entity' => $entity]);

            $this->executeDynamicMethod('persist<EntityName>Entity', [$entity]);
            $this->dispatcher->dispatch(new ImportAddedEvent($entity), ImportAddedEvent::NAME);

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