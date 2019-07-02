<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\TempProdCount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TempProdCount|null find($id, $lockMode = null, $lockVersion = null)
 * @method TempProdCount|null findOneBy(array $criteria, array $orderBy = null)
 * @method TempProdCount      findAll()
 * @method TempProdCount      findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TempProdCountRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TempProdCount::class);
    }

    // /**
    //  * @return TempProdCount[] Returns an array of TempProdCount objects
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
    public function findOneBySomeField($value): ?TempProdCount
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
