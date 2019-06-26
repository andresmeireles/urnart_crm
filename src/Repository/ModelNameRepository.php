<?php

namespace App\Repository;

use App\Entity\ModelName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ModelName|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModelName|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModelName[]    findAll()
 * @method ModelName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelNameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ModelName::class);
    }

    // /**
    //  * @return ModelName[] Returns an array of ModelName objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ModelName
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
