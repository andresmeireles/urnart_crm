<?php declare(strict_types = 1);
declare(strict_types = 1);

namespace App\Model;

use App\Entity\Order;
use App\Utils\Andresmei\StringConvertions;
use App\Utils\Exceptions\ListNotExistsException;

final class ListModel extends Model
{
    /**
     * @param string $type
     * @return array
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
     * @return  array  App\Entity\Order.
     */
    public function getParsedClientData(): array
    {
        return $this->entityManager->getRepository(Order::class)->findByClientGroup();
    }

    /**
     * @return  array  array App\Entity\Order.
     */
    public function getParsedLastOrderData(): array
    {
        return $this->entityManager->getRepository(Order::class)->findByLastOrder();
    }

    /**
     * @param int $type
     * @return array
     */
    public function getParsedStatusOrderData(int $type = 0): array
    {
        return $this->entityManager->getRepository(Order::class)->findByStatusOrders($type);
    }

    /**
     * @param string $repository
     * @param string $orderBy
     * @return string
     */
    public function getJsonListOrderBy(string $repository, string $orderBy): string
    {
        $returnList = $this->entityManager->getRepository('App\Entity\\' . $repository)->findBy(
            [],
            [$orderBy => 'ASC']
        );
        return $this->serializer->serialize($returnList, 'json');
    }

    /**
     * @param string $repository
     * @param string $orderBy
     * @return array
     */
    public function getListOrderBy(string $repository, string $orderBy): array
    {
        return $this->entityManager->getRepository('App\Entity\\' . $repository)->findBy(
            [],
            [$orderBy => 'ASC']
        );
    }

    /**
     * @param string $repository
     * @param string|null $beginDate
     * @param string|null $lastDate
     * @return array
     * @throws \Exception
     */
    public function getListByDate(string $repository, ?string $beginDate, ?string $lastDate): array
    {
        $repo = 'App\Entity\\' . $repository;
        $convertedBeginDate = (new StringConvertions())->strToDateString($beginDate);
        $convertedLastDate = (new StringConvertions())->strToDateString($lastDate);
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $result = null;
        if ($convertedBeginDate === null && $convertedLastDate === null) {
            $result = $queryBuilder->select('u')->from($repo, 'u')->orderBy('u.id', 'ASC');
        }

        if ($convertedBeginDate !== null && $convertedLastDate !== null) {
            $result = $queryBuilder->select('u')
                ->from($repo, 'u')
                ->where('u.createDate BETWEEN :begin AND :last')
                ->setParameter('begin', $convertedBeginDate)
                ->setParameter('last', $convertedLastDate)
                ->orderBy('u.id', 'ASC');
        }

        if ($convertedBeginDate === null && $convertedLastDate !== null) {
            $result = $queryBuilder->select('u')
                ->from($repo, 'u')
                ->where('u.createDate <= :date')
                ->setParameter('date', sprintf('%s 23:00:00', $convertedLastDate))
                ->orderBy('u.id', 'ASC');
        }

        if ($convertedBeginDate !== null && $convertedLastDate === null) {
            $result = $queryBuilder->select('u')
                ->from($repo, 'u')
                ->where('u.createDate >= :date')
                ->setParameter('date', $convertedBeginDate)
                ->orderBy('u.id', 'ASC');
        }
        return $result->getQuery()->getResult();
    }
}
