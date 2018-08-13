<?php

namespace App\Repository;

use App\Entity\FeedstockInvetory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FeedstockInvetory|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedstockInvetory|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedstockInvetory[]    findAll()
 * @method FeedstockInvetory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedstockInvetoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FeedstockInvetory::class);
    }

//    /**
//     * @return FeedstockInvetory[] Returns an array of FeedstockInvetory objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FeedstockInvetory
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
