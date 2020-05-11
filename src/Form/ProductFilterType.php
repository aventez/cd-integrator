<?php

namespace App\Form;

use App\Entity\ProductImport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('coffeeDeskId', IntegerType::class, [
                'label' => 'app.product.filter.id.label'
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'app.product.filter.submit.label'
            ]);
    }

    public function getBlockPrefix()
    {
        return 'product_filter';
    }
}