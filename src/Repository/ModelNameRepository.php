<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ModelName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ModelName|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModelName|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModelName      findAll()
 * @method ModelName      findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ModelNameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ModelName::class);
    }

    public function getAllHeightsWithSpecificities()
    {
        $queryConsult = sprintf(
            "SELECT DISTINCT CONCAT(p.height,' ',COALESCE(p.specificity, '')) FROM %s p",
            ModelName::class
        );
        $query = $this->getEntityManager()->createQuery($queryConsult);
        return $query->execute();
    }

    /**
     * @param string $search
     * @return array|null|ModelName
//     */
//    public function findSingleDistinctItemsInTimeInterval(string $search): ?array
//    {
//        $entityManager = $this->getEntityManager();
//        $queryConsult = sprintf(
//            'SELECT DISTINCT(%s) FROM %s p WHERE p.date',
//            $search,
//            ModelName::class
//        );
//        $query = $entityManager->createQuery($queryConsult);
//        return $query->execute();
//    }

//    public function findMultipleDistinctItems(iterable $items)
//    {
//        $itemsForConsult = array_map(static function ($item) { return sprintf('p.%s', $item); }, (array) $items);
//        $itemsForConsult = implode(",' ',", $itemsForConsult);
//        $itemsForConsult = trim($itemsForConsult, ' ,');
//        $entityManager = $this->getEntityManager();
//        $queryConsult = sprintf(
//            "SELECT DISTINCT COALESCE(CONCAT(%s), '') FROM %s p",
//            $itemsForConsult,
//            ModelName::class
//        );
//        $query = $entityManager->createQuery($queryConsult);
//        return $query->execute();
//    }

    // /**
    //  * @return ModelName[] Returns an array of ModelName objects
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
    public function findOneBySomeField($value): ?ModelName
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
