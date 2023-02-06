<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ProductionCount;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
// use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\ResultSetMapping;

/**
 * @method ProductionCount|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProductionCount|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProductionCount      findAll()
 * @method ProductionCount      findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ProductionCountRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductionCount::class);
    }

    public function getYearReport(string $year): array
    {
        $em = $this->getEntityManager()->getConnection();
        $queryString = "SELECT MONTH(date) AS 'month', sum(amount) AS 'value' 
        FROM production_count 
        WHERE MONTH(date) BETWEEN 01 AND 12 AND YEAR(date) = ? GROUP BY MONTH(date)";

        $query = $em->prepare($queryString);
        $query->bindValue(1, $year);
        $query->execute();

        return $query->fetchAll();
    }

    /**
     * @return array|ProductionCount|null
     */
    public function findInTimeInterval(
        array $search,
        \DateTimeInterface $beginDate,
        \DateTimeInterface $endDate
    ): ?array {
        $searchList = '';
        foreach ($search as $ser) {
            $searchList .= 'p.'.$ser;
        }
        $searchList = trim($searchList, ',');
        $entityManager = $this->getEntityManager();
        $queryConsult = sprintf(
            'SELECT DISTINCT(%s) FROM %s p WHERE p.date BETWEEN :bdate AND :edate',
            $searchList,
            ProductionCount::class
        );
        $query = $entityManager->createQuery($queryConsult)
            ->setParameter('bdate', $beginDate->format('Y-m-d 00:00:00'))
            ->setParameter('edate', $endDate->format('Y-m-d 23:59:59'));

        return $query->execute();
    }

    public function getMonthProductionByDate(\DateTimeInterface $dateTime): array
    {
        $sqlQuery = "SELECT sum(prodCount.amount) AS valor FROM production_count prodCount WHERE date BETWEEN :begin AND :end";
        $stmt = $this->getEntityManager()->getConnection();
        $query = $stmt->prepare($sqlQuery);
        $query->bindValue(
            ':begin',
            sprintf('%s-%s-01', $dateTime->format('Y'), $dateTime->format('m'))
        );
        $query->bindValue(
            ':end',
            sprintf('%s-%s-%s', $dateTime->format('Y'), $dateTime->format('m'), $dateTime->format('d'))    
        );
        $query->execute();
        
        return $query->fetch();
    }

    public function getAmountByDateRange(\DateTimeInterface $beginDate, \DateTimeInterface $endDate): ?string
    {
        return $this->getEntityManager()->createQuery(
            'SELECT SUM(p.amount) FROM App\Entity\ProductionCount p WHERE p.date BETWEEN :bdate AND :edate'
        )->setParameter('bdate', $beginDate->format('Y-m-d 00:00:00'))
         ->setParameter('edate', $endDate->format('Y-m-d 23:59:59'))
         ->execute()[0][1] ?? null;
    }

    public function getProductionReportByMonth(int $monthNumber, int $year): array
    {
        $stmt = $this->getEntityManager()->getConnection();
        $agglometaredResultQuery = [];
        $amountQuery = "SELECT SUM(prodCount.amount) AS valor FROM production_count prodCount WHERE MONTH(date) = :monthstmt AND YEAR(date) = :yearstmt";
        $query = $stmt->prepare($amountQuery);
        $query->bindValue(':monthstmt', $monthNumber);
        $query->bindValue(':yearstmt', $year);
        $query->execute();
        $agglometaredResultQuery['amount'] = $query->fetch()['valor'];
        $heightAndModels = "SELECT DISTINCT model, TRIM(CONCAT(height, ' ', COALESCE(obs, ''))) AS height FROM production_count WHERE MONTH(date) = :monthstmt AND YEAR(date) = :yearstmt ORDER BY model ASC";
        $query = $stmt->prepare($heightAndModels);
        $query->bindValue(':monthstmt', $monthNumber);
        $query->bindValue(':yearstmt', $year);
        $query->execute();
        $result = $query->fetchAll();
        $model = array_map(fn ($queryResult) => $queryResult['model'], $result);
        $height = array_map(fn ($queryResult) => $queryResult['height'], $result);
        $agglometaredResultQuery['model'] = array_unique($model);
        $agglometaredResultQuery['height'] = array_unique($height);
        $heightAndAmount = "SELECT TRIM(CONCAT(model, ' ', height, ' ', COALESCE(obs, ''))) AS names, SUM(amount) AS itemamount FROM production_count WHERE MONTH(date) = :monthstmt AND YEAR(date) = :yearstmt GROUP BY names ORDER BY names ASC";
        $query = $stmt->prepare($heightAndAmount);
        $query->bindValue(':monthstmt', $monthNumber);
        $query->bindValue(':yearstmt', $year);
        $query->execute();
        $result = $query->fetchAll();
        $amount = array_map(fn ($queryResult) => $queryResult['itemamount'], $result);
        $names = array_map(fn ($queryResult) => $queryResult['names'], $result);
        $agglometaredResultQuery['products'] = array_combine($names, $amount);

        return $agglometaredResultQuery;
    }

    public function getDistinctProductAmountsByDate(
        \DateTimeInterface $beginDate,
        \DateTimeInterface $endDate
    ): ?array {
        $occurrences = [];
        $dqlQuery = sprintf(
            "SELECT CONCAT(p.model, ' ', p.height, ' ', COALESCE(p.obs, '')) AS name, p.amount FROM %s p WHERE p.date BETWEEN :bdate AND :edate",
            ProductionCount::class
        );
        $entityManager = $this->getEntityManager();
        $results = $entityManager->createQuery($dqlQuery)
                        ->setParameter('bdate', $beginDate->format('Y-m-d 00:00:00'))
                        ->setParameter('edate', $endDate->format('Y-m-d 23:59:59'))
                        ->execute();
        array_walk($results, static function ($result) use (&$occurrences) {
            $name = trim($result['name']);
            $amount = $result['amount'];
            if (array_key_exists($name, $occurrences)) {
                $occurrences[$name] += $amount;
            } else {
                $occurrences[$name] = $amount;
            }
        });

        return $occurrences;
    }

    public function getProductHeightsListByDate(
        \DateTimeInterface $beginDate,
        \DateTimeInterface $endDate
    ): ?array {
        $allProductsList = [];

        $dqlQuery = sprintf(
            "SELECT DISTINCT CONCAT(p.height, ' ', COALESCE(p.obs, '')) AS name FROM %s p WHERE p.date BETWEEN :bdate AND :edate",
            ProductionCount::class
        );
        $entityManager = $this->getEntityManager();
        $results = $entityManager->createQuery($dqlQuery)
            ->setParameter('bdate', $beginDate->format('Y-m-d 00:00:00'))
            ->setParameter('edate', $endDate->format('Y-m-d 23:59:59'))
            ->execute();

        array_walk($results, static function ($result) use (&$allProductsList) {
            $allProductsList[] = trim($result['name']);
        });

        return $allProductsList;
    }

    // /**
    //  * @return ProductionCount[] Returns an array of ProductionCount objects
    //  */
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
    public function findOneBySomeField($value): ?ProductionCount
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
