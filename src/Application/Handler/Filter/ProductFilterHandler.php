<?php

namespace App\Application\Handler\Filter;

use App\Repository\ProductRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;

class ProductFilterHandler
{
    private $filterBuilderUpdater;
    private $repository;

    public function __construct(FilterBuilderUpdater $filterBuilderUpdater, ProductRepository $repository)
    {
        $this->filterBuilderUpdater = $filterBuilderUpdater;
        $this->repository = $repository;
    }

    public function handle(FormInterface $form): QueryBuilder
    {
        $queryBuilder = $this->repository->createQueryBuilder('product_filter');
        $this->filterBuilderUpdater->addFilterConditions($form, $queryBuilder);

        return $queryBuilder;
    }
}