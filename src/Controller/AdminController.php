<?php

namespace App\Controller;

use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;
use Symfony\Component\Form\Form;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class AdminController extends MainController
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    protected function editAction()
    {
        $this->dispatch(EasyAdminEvents::PRE_EDIT);

        $id = $this->request->query->get('id');
        $easyadmin = $this->request->attributes->get('easyadmin');
        $entity = $easyadmin['item'];

        $fields = $this->entity['edit']['fields'];

        $editForm = $this->executeDynamicMethod('create<EntityName>EditForm', [$entity, $fields]);
        $deleteForm = $this->createDeleteForm($this->entity['name'], $id);

        $editForm->handleRequest($this->request);
        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $encodedPassword = $this->passwordEncoder->encodePassword(
                $entity,
                $entity->getPassword()
            );
            $entity->setPassword($encodedPassword);

            $this->dispatch(EasyAdminEvents::PRE_UPDATE, ['entity' => $entity]);
            $this->executeDynamicMethod('update<EntityName>Entity', [$entity]);
            $this->dispatch(EasyAdminEvents::POST_UPDATE, ['entity' => $entity]);

            return $this->redirectToReferrer();
        }

        $this->dispatch(EasyAdminEvents::POST_EDIT);

        $parameters = [
            'form' => $editForm->createView(),
            'entity_fields' => $fields,
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];

        return $this->executeDynamicMethod('render<EntityName>Template', ['edit', $this->entity['templates']['edit'], $parameters]);
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
            $encodedPassword = $this->passwordEncoder->encodePassword(
                $entity,
                $entity->getPassword()
            );
            $entity->setPassword($encodedPassword);
            $entity->setRoles(['ROLE_ADMIN']);

            $this->dispatch(EasyAdminEvents::PRE_PERSIST, ['entity' => $entity]);
            $this->executeDynamicMethod('persist<EntityName>Entity', [$entity]);
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
