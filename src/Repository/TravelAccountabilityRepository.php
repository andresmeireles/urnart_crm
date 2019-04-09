<?php

namespace App\Repository;

use App\Entity\TravelAccountability;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TravelAccountability|null find($id, $lockMode = null, $lockVersion = null)
 * @method TravelAccountability|null findOneBy(array $criteria, array $orderBy = null)
 * @method TravelAccountability[]    findAll()
 * @method TravelAccountability[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravelAccountabilityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TravelAccountability::class);
    }

    // /**
    //  * @return TravelAccountability[] Returns an array of TravelAccountability objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TravelAccountability
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
