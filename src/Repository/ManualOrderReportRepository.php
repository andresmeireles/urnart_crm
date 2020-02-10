<?php declare(strict_types = 1);

namespace App\Repository;

use App\Entity\ManualOrderReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
/**
 * @method ManualOrderReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ManualOrderReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ManualOrderReport      findAll()
 * @method ManualOrderReport      findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class ManualOrderReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ManualOrderReport::class);
    }

    public function getSellReport(\DateTime $beginDate, \DateTime $endDate): array
    {
        $sqlQuery = "SELECT mor.create_date AS cdate, mor.customer_name AS cname, mor.customer_city AS city, SUM(mpc.product_amount) AS amount FROM manual_order_report mor JOIN manual_product_cart mpc ON mpc.manual_order_report_id = mor.id WHERE MONTH(mor.create_date) IN (:m) AND DAY(mor.create_date) BETWEEN :bday AND :eday AND YEAR(mor.create_date) IN (:y) AND mor.create_date IS NOT NULL GROUP BY cdate, cname, city";
        $stmt = $this->getEntityManager()->getConnection();
        $query = $stmt->prepare($sqlQuery);
        $query->bindValue(':y', $beginDate->format('Y'));
        $query->bindValue(':m', $beginDate->format('m'));
        $query->bindValue(':bday', $beginDate->format('d'));
        $query->bindValue(':eday', $endDate->format('d'));
        $query->execute();

        return $query->fetchAll();
    }

    public function getSellReportChartData(\DateTime $beginDate, \DateTime $endDate): array
    {
        $sqlQuery = "SELECT DATE(mor.create_date) AS dt, SUM(mpc.product_amount) AS amount FROM manual_order_report mor JOIN manual_product_cart mpc ON mpc.manual_order_report_id = mor.id WHERE MONTH(mor.create_date) IN (:m) AND YEAR(mor.create_date) IN (:y) AND DAY(mor.create_date) BETWEEN :bday AND :eday AND mor.create_date IS NOT NULL GROUP BY dt";
        $stmt = $this->getEntityManager()->getConnection();
        $query = $stmt->prepare($sqlQuery);
        $query->bindValue(':y', $beginDate->format('Y'));
        $query->bindValue(':m', $beginDate->format('m'));
        $query->bindValue(':bday', $beginDate->format('d'));
        $query->bindValue(':eday', $endDate->format('d'));
        $query->execute();

        return $query->fetchAll();
    }

    public function getSellReportInRange(\DateTime $beginDate, \DateTime $endDate): array
    {
        $sqlQuery = "SELECT mor.create_date AS cdate, mor.customer_name AS cname, mor.customer_city AS city, SUM(mpc.product_amount) AS amount FROM manual_order_report mor JOIN manual_product_cart mpc ON mpc.manual_order_report_id = mor.id WHERE DATE(mor.create_date) BETWEEN :bdate AND :edate AND mor.create_date IS NOT NULL GROUP BY cdate, cname, city";
        $stmt = $this->getEntityManager()->getConnection();
        $query = $stmt->prepare($sqlQuery);
        $query->bindValue(
            ':bdate',
            sprintf('%s-%s-%s', $beginDate->format('Y'), $beginDate->format('m'), $beginDate->format('d'))
        );
        $query->bindValue(
            ':edate',
            sprintf('%s-%s-%s', $endDate->format('Y'), $endDate->format('m'), $endDate->format('d'))
        );
        $query->execute();

        return $query->fetchAll();
    }

    public function getSellReportChartDataInRange(\DateTime $beginDate, \DateTime $endDate): array
    {
        $sqlQuery = "SELECT DATE(mor.create_date) AS dt, SUM(mpc.product_amount) AS amount FROM manual_order_report mor JOIN manual_product_cart mpc ON mpc.manual_order_report_id = mor.id WHERE DATE(mor.create_date) BETWEEN :bdate AND :edate AND mor.create_date IS NOT NULL GROUP BY dt";
        $stmt = $this->getEntityManager()->getConnection();
        $query = $stmt->prepare($sqlQuery);
        $query->bindValue(
            ':bdate',
            sprintf('%s-%s-%s', $beginDate->format('Y'), $beginDate->format('m'), $beginDate->format('d'))
        );
        $query->bindValue(
            ':edate',
            sprintf('%s-%s-%s', $endDate->format('Y'), $endDate->format('m'), $endDate->format('d'))
        );
        $query->execute();

        return $query->fetchAll();
    }

    public function changeOrderStatus(ManualOrderReport $orderRegistry, int $status): bool
    {
        $order = $this->find($orderRegistry);
        $order->setOrderStatus($status);
        try {
            $dbEm = $this->getEntityManager();
            $dbEm->merge($order);
            $dbEm->flush();

            return true;
        } catch (\PDOException $err) {
            throw new \PDOException($err->getMessage());
        }
    }

    /**
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
