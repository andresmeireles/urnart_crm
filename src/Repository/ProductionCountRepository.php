<?php

namespace App\Repository;

use App\Entity\ProductionCount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductionCount|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductionCount|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductionCount[]    findAll()
 * @method ProductionCount[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductionCountRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductionCount::class);
    }

    // /**
    //  * @return ProductionCount[] Returns an array of ProductionCount objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ProductionCount
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
