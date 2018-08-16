<?php

namespace App\Repository;

use App\Entity\FeedstockInventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method FeedstockInventory|null find($id, $lockMode = null, $lockVersion = null)
 * @method FeedstockInventory|null findOneBy(array $criteria, array $orderBy = null)
 * @method FeedstockInventory[]    findAll()
 * @method FeedstockInventory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedstockInventoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FeedstockInventory::class);
    }

//    /**
//     * @return FeedstockInventory[] Returns an array of FeedstockInventory objects
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
    public function findOneBySomeField($value): ?FeedstockInventory
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
