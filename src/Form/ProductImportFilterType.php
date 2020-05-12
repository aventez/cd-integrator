<?php

namespace App\Form;

use App\Application\Enum\ProductImportStatusEnum;
use App\Entity\ProductImport;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Lexik\Bundle\FormFilterBundle\Filter\Form\Type\ChoiceFilterType;

class ProductImportFilterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('originalId', IntegerType::class, [
                'label' => 'app.importProduct.filter.originalId.label',
                'required' => false,
            ])
            ->add('status', ChoiceFilterType::class, [
                'label' => 'app.importProduct.filter.status.label',
                'placeholder' => 'app.importProduct.filter.status.placeholder',
                'choices' => [
                    'app.importProduct.status.new' => ProductImportStatusEnum::NEW,
                    'app.importProduct.status.processing' => ProductImportStatusEnum::PROCESSING,
                    'app.importProduct.status.completed' => ProductImportStatusEnum::COMPLETED,
                    'app.importProduct.status.rejected' => ProductImportStatusEnum::REJECTED,
                ],
                'required' => false
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'app.importProduct.filter.submit.label'
            ]);
    }

    public function getBlockPrefix()
    {
        return 'product_import_filter';
    }
}