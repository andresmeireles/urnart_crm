<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ProductInventory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProductInventory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductInventory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductInventory      findAll()
 * @method ProductInventory      findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ProductInventoryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProductInventory::class);
    }

//    /**
//     * @return ProductInventory[] Returns an array of ProductInventory objects
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
    public function findOneBySomeField($value): ?ProductInventory
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
