<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\TravelTruckOrders;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
/**
 * @method TravelTruckOrders|null find($id, $lockMode = null, $lockVersion = null)
 * @method TravelTruckOrders|null findOneBy(array $criteria, array $orderBy = null)
 * @method TravelTruckOrders      findAll()
 * @method TravelTruckOrders      findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TravelTruckOrdersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TravelTruckOrders::class);
    }

    // /**
    //  * @return TravelTruckOrders[] Returns an array of TravelTruckOrders objects
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
    public function findOneBySomeField($value): ?TravelTruckOrders
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
