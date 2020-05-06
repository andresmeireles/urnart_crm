<?php

namespace App\Repository;

use App\Entity\ManualOrderReport;
use App\Entity\OrderPrediction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method OrderPrediction|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderPrediction|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderPrediction[]    findAll()
 * @method OrderPrediction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderPredictionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OrderPrediction::class);
    }

    /**
     * 
     * @return OrderPrediction[]
     */
    public function predictedOrders(): array
    {
        return $this->findBy(['active' => true], ['id' => 'desc']);
    }

    public function populate(): void
    {
        $em = $this->getEntityManager();
        $allOrder = $em->getRepository(ManualOrderReport::class)->findBy(['active' => true]);
        /** @var ManualOrderReport $value */
        foreach ($allOrder as $value) {
            if ($this->findOneBy(['orderId' => $value->getId()]) !== null) {
                continue;
            }
            $orderPrediction = new OrderPrediction();
            $orderPrediction->setUpdatedAt(new \DateTime(('now')));
            $orderPrediction->setPredictionDate(null);
            $orderPrediction->setOrderId($value);
            $orderPrediction->setActive(true);
            $em->persist($orderPrediction);
            $em->flush();
        }
    }

    // /**
    //  * @return OrderPrediction[] Returns an array of OrderPrediction objects
    //  */
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
    public function findOneBySomeField($value): ?OrderPrediction
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
