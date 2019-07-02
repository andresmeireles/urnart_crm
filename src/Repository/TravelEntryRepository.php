<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\TravelEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TravelEntry|null find($id, $lockMode = null, $lockVersion = null)
 * @method TravelEntry|null findOneBy(array $criteria, array $orderBy = null)
 * @method TravelEntry[]    findAll()
 * @method TravelEntry[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TravelEntryRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TravelEntry::class);
    }

    // /**
    //  * @return TravelEntry[] Returns an array of TravelEntry objects
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
    public function findOneBySomeField($value): ?TravelEntry
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
