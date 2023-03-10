<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ManualProductCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
// use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ManualProductCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManualProductCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManualProductCart    findAll()
 * @method ManualProductCart    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ManualProductCartRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ManualProductCart::class);
    }

    /**
     * @return array|ManualProductCart
     */
    public function findModelNames(): array
    {
        return $this->getEntityManager()->createQuery(
            'SELECT DISTINCT(u.productName) FROM App\Entity\ManualProductCart u ORDER BY u.productName ASC'
        )->execute();
    }

    // /**
    //  * @return ManualProductCart[] Returns an array of ManualProductCart objects
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
    public function findOneBySomeField($value): ?ManualProductCart
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
