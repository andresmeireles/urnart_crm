<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Order|null find($id, $lockMode = null, $lockVersion = null)
 * @method Order|null findOneBy(array $criteria, array $orderBy = null)
 * @method Order      findAll()
 * @method Order      findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @return array|Order
     */
    public function findByClientGroup(): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.customer', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array|Order
     */
    public function findByLastOrder(): array
    {
        return $this->createQueryBuilder('o')
            ->orderBy('o.createDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

    /**
     * @param int $type
     * @return array|Order
     */
    public function findByStatusOrders(int $type = 0): array
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.reserved = :val')
            ->setParameter('val', $type)
            ->orderBy('o.createDate', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Order[] Returns an array of Order objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Order
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
