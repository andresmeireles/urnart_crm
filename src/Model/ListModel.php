<?php
declare(strict_types = 1);

namespace App\Model;

use App\Entity\Order;
use App\Utils\Andresmei\StringConvertions;

class ListModel extends Model
{
    public function select(string $type)
    {
        switch ($type) {
            case 'client':
                return $this->getParsedClientData();
                break;
            case 'last':
                return $this->getParsedLastOrderData();
                break;
            case 'open':
                return $this->getParsedStatusOrderData();
                break;
            case 'closed':
                return $this->getParsedStatusOrderData(1);
                break;
            case 'reserved':
                return $this->getParsedStatusOrderData(2);
                break;
            default:
                return 'tipo de relatorio não existe';
                break;
        }
    }

    public function getParsedClientData()
    {
        return $this->em->getRepository(Order::class)->findByClientGroup();
    }

    public function getParsedLastOrderData()
    {
        return $this->em->getRepository(Order::class)->findByLastOrder();
    }

    public function getParsedStatusOrderData(int $type = 0)
    {
        return $this->em->getRepository(Order::class)->findByStatusOrders($type);
    }

    public function getJsonListOrderBy(string $repository, string $orderBy): string
    {
        $returnList = $this->em->getRepository('App\Entity\\'.$repository)->findBy(
            array(),
            array($orderBy => 'ASC')
        );
        $jsonResponse = $this->serializer->serialize($returnList, 'json');

        return $jsonResponse;
    }

    public function getListOrderBy(string $repository, string $orderBy): array
    {        
        $returnList = $this->em->getRepository('App\Entity\\'.$repository)->findBy(
            array(),
            array($orderBy => 'ASC')
        );

        return $returnList;
    }

    /**
     * Lista por data dados de um repositorio.
     *
     * @param string $repository
     * @param string|null $beginDate
     * @param string|null $lastDate
     * @return array
     */
    public function getListByDate(string $repository, ?string $beginDate, ?string $lastDate): array
    {
        $repo = 'App\Entity\\'.$repository;
        $convertedBeginDate = (new StringConvertions())->strToDateString($beginDate);
        $convertedLastDate = (new StringConvertions())->strToDateString($lastDate);
        $queryBuilder = $this->em->createQueryBuilder();
        $result = null;
        if (is_null($convertedBeginDate) && is_null($convertedLastDate)) {
            $result = $queryBuilder->select('u')->from($repo, 'u')->orderBy('u.id', 'ASC');
        }

        if (!is_null($convertedBeginDate) && !is_null($convertedLastDate)) {
            $result = $queryBuilder->select('u')
                                   ->from($repo, 'u')
                                   ->where('u.createDate BETWEEN :begin AND :last')
                                   ->setParameter('begin', $convertedBeginDate)
                                   ->setParameter('last', $convertedLastDate)
                                   ->orderBy('u.id', 'ASC');
        }

        if (is_null($convertedBeginDate) && !is_null($convertedLastDate)) {
            $result = $queryBuilder->select('u')
                                   ->from($repo, 'u')
                                   ->where('u.createDate <= :date')
                                   ->setParameter('date', $convertedLastDate)
                                   ->orderBy('u.id', 'ASC');
        }

        if (!is_null($convertedBeginDate) && is_null($convertedLastDate)) {
            $result = $queryBuilder->select('u')
                                   ->from($repo, 'u')
                                   ->where('u.createDate >= :date')
                                   ->setParameter('date', $convertedBeginDate)
                                   ->orderBy('u.id', 'ASC');
        }
        return $result->getQuery()->getResult();
    }
}
