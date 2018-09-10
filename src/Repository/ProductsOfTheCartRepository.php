<?php

namespace App\Repository;

use App\Entity\ProductsOfTheCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductsOfTheCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductsOfTheCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductsOfTheCart[]    findAll()
 * @method ProductsOfTheCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProductsOfTheCartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductsOfTheCart::class);
    }

//    /**
//     * @return ProductsOfTheCart[] Returns an array of ProductsOfTheCart objects
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
    public function findOneBySomeField($value): ?ProductsOfTheCart
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
