<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ManualOrderReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ManualOrderReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManualOrderReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManualOrderReport      findAll()
 * @method ManualOrderReport      findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ManualOrderReportRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ManualOrderReport::class);
    }

    /**
     * Undocumented function
     *
     * @param string ...$fields
     * @return array|ManualOrderReport
     */
    public function someFieldsConsult(string ...$fields): array
    {
        $queryConsult = '';
        $manager = $this->getEntityManager();
        foreach ($fields as $field) {
            $queryConsult .= sprintf('u.%s, ', $field);
        }
        $query = $manager->createQuery(
            sprintf(
                'SELECT %s FROM App\Entity\ManualOrderReport u WHERE u.active = :param',
                trim($queryConsult, ', ')
            )
        )->setParameter('param', true);

        return $query->execute();
    }

    // /**
    //  * @return ManualOrderReport[] Returns an array of ManualOrderReport objects
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
    public function findOneBySomeField($value): ?ManualOrderReport
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
