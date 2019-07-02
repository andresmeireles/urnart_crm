<?php
declare(strict_types = 1);

namespace App\Model;

use App\Entity\Order;
use App\Utils\Andresmei\MyDateTime;
use App\Utils\Andresmei\StdResponse;
use App\Utils\Andresmei\StringConvertions;
use App\Utils\Exceptions\ListNotExistsException;
use phpDocumentor\Reflection\Types\Mixed_;

/**
 * TEST 
 * 
 * yearOrderReport
 * getParsedClientData
 * getParsedLastOrderData
 * getParsedStatusOrderData
 * getJsonListOrderBy
 * getListOrderBy
 * getListByDate
 * 
 */
class ListModel extends Model
{
    /**
     * @param string $type
     * @param null $optionalParameter
     *
     * @return string|array
     * @throws ListNotExistsException
     */
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
                throw new ListNotExistsException(
                    sprintf(
                        'A lista %s não existe ou inserida incorretamente. Favor verificar a lista',
                        $type
                    )
                );
                break;
        }
    }

    /**
     * Retorna pedidos por cliente.
     *
     * @return  array  App\Entity\Order.
     */
    public function getParsedClientData(): array
    {
        return $this->em->getRepository(Order::class)->findByClientGroup();
    }

    /**
     * Retorna ultimo pedido.
     *
     * @return  array  array App\Entity\Order.
     */
    public function getParsedLastOrderData(): array
    {
        return $this->em->getRepository(Order::class)->findByLastOrder();
    }

    /**
     * Retorna lista de elemetos de acordo com status.
     *
     * @param int $type  opções 0 ou 1 ou 2.
     *
     * @return array
     */
    public function getParsedStatusOrderData(int $type = 0): array
    {
        return $this->em->getRepository(Order::class)->findByStatusOrders($type);
    }

    /**
     * Retorna resultados em dada ordem em formato json.
     *
     * @param string $repository
     * @param string $orderBy
     * @return string
     */
    public function getJsonListOrderBy(string $repository, string $orderBy): string
    {
        $returnList = $this->em->getRepository('App\Entity\\'.$repository)->findBy(
            [],
            [$orderBy => 'ASC']
        );
        return $this->serializer->serialize($returnList, 'json');
    }

    /**
     * Lista resultados de repositorio dada a orderm.
     *
     * @param string $repository
     * @param string $orderBy
     * @return array
     */
    public function getListOrderBy(string $repository, string $orderBy): array
    {
        return $this->em->getRepository('App\Entity\\'.$repository)->findBy(
            [],
            [$orderBy => 'ASC']
        );
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
                                   ->setParameter('date', sprintf('%s 23:00:00', $convertedLastDate))
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
