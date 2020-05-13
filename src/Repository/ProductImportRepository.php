<?php

namespace App\Repository;

use App\Entity\ProductImport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method ProductImport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductImport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductImport[]    findAll()
 * @method ProductImport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductImportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductImport::class);
    }

    public function save(ProductImport $import): void
    {
        $this->_em->persist($import);
        $this->_em->flush();
    }
}
