<?php

namespace App\Form\Filter\Type;

use App\Application\Enum\ProductImportStatusEnum;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Form\Filter\Type\FilterType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImportStatusFilterType extends FilterType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choices' => [
                'app.import.status.new' => ProductImportStatusEnum::NEW,
                'app.import.status.processing' => ProductImportStatusEnum::PROCESSING,
                'app.import.status.completed' => ProductImportStatusEnum::COMPLETED,
                'app.import.status.rejected' => ProductImportStatusEnum::REJECTED,
            ],
            'multiple' => false,
            'required' => true,
        ]);
    }

    public function filter(QueryBuilder $queryBuilder, FormInterface $form, array $metadata)
    {
        $status = $form->getData();
        $queryBuilder->andWhere('entity.status = :status')
            ->setParameter('status', $status);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }
}