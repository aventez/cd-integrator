<?php


namespace App\Application\Handler\Filter;

use App\Repository\ProductImportRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdater;

class ProductImportFilterHandler
{
    private $filterBuilderUpdater;
    private $repository;

    public function __construct(FilterBuilderUpdater $filterBuilderUpdater, ProductImportRepository $repository)
    {
        $this->filterBuilderUpdater = $filterBuilderUpdater;
        $this->repository = $repository;
    }

    public function handle(FormInterface $form): QueryBuilder
    {
        $queryBuilder = $this->repository->createQueryBuilder('product_import_filter')
            ->addOrderBy('product_import_filter.id', 'DESC');

        $this->filterBuilderUpdater->addFilterConditions($form, $queryBuilder);

        return $queryBuilder;
    }
}