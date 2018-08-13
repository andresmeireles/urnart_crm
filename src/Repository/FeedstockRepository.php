<?php

namespace App\Repository;

use App\Entity\Feedstock;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Feedstock|null find($id, $lockMode = null, $lockVersion = null)
 * @method Feedstock|null findOneBy(array $criteria, array $orderBy = null)
 * @method Feedstock[]    findAll()
 * @method Feedstock[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FeedstockRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Feedstock::class);
    }

//    /**
//     * @return Feedstock[] Returns an array of Feedstock objects
//     */
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
    public function findOneBySomeField($value): ?Feedstock
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
