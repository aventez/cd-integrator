<?php

namespace App\Form;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'app.offerToCreate.create.form.name'
            ])
            ->add('price', NumberType::class, [
                'label' => 'app.offerToCreate.create.form.price'
            ])
            ->add('description', TextType::class, [
                'label' => 'app.offerToCreate.create.form.description'
            ])
            ->add('shortDescription', TextType::class, [
                'label' => 'app.offerToCreate.create.form.shortDescription'
            ])
            ->add('images', CollectionType::class, [
                'label' => 'app.offerToCreate.create.form.images'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'app.offerToCreate.create.form.submit'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class
        ]);
    }
}