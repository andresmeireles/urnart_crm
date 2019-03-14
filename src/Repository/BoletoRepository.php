<?php

namespace App\Repository;

use App\Entity\Boleto;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Boleto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Boleto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Boleto[]    findAll()
 * @method Boleto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoletoRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Boleto::class);
    }

    // /**
    //  * @return Boleto[] Returns an array of Boleto objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Boleto
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
