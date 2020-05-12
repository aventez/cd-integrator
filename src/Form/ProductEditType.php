<?php

namespace App\Form;

use App\Entity\Product;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price', NumberType::class, [
                'label' => 'app.product.update.price.label'
            ])
            ->add('buffer', NumberType::class, [
                'label' => 'app.product.update.buffer.label'
            ])
            ->add('syncDisabled', CheckboxType::class, [
                'label' => 'app.product.update.syncDisabled.label',
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'app.product.update.submit.label',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
           'data_class' => Product::class
        ]);
    }
}