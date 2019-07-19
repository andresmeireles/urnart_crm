<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\ManualOrderReport;
use App\Entity\ManualProductCart;
use App\Entity\TravelTruckOrders;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\MyDateTime;
use Doctrine\Common\Collections\Collection;

/**
 * Class TravelTruckOrderMode
 * @package App\Model
 */
final class TravelTruckOrderModel extends Model
{
    private static $manipulableArray = [];

    /**
     * @param TravelTruckOrders $orderTruckEntity
     * @param array $reportData
     * @param array $orders
     * @return FlashResponse
     * @throws \Exception
     */
    public function editTruckDepartureReport(
        TravelTruckOrders $orderTruckEntity,
        array $reportData,
        array $orders
    ): FlashResponse {
        $entityManager = $this->entityManager;
        $manualOrderRepository = $entityManager->getRepository(ManualOrderReport::class);
        /** @var TravelTruckOrders $truckReport */
        $truckReport = $entityManager->getRepository(TravelTruckOrders::class)
            ->find($orderTruckEntity->getId());
        try {
            $truckReport->setDriverName($reportData['driverName']);
            $truckReport->setKmout($reportData['kmout']);
            $date = new MyDateTime($reportData['departureDate'], 'America/Belem');
            $truckReport->setDepartureDate($date);
            $truckReport->getOrderId()->map(static function ($value) use ($truckReport) {
                $truckReport->removeOrderId($value);
            });
            foreach ($orders as $order) {
                $simpleArray = [
                    $order['id'] => isset($order['isChecked']) ? (bool) $order['isChecked'] : false
                ];
                $manualOrderReport = $manualOrderRepository->find($order['id']);
                $truckReport->addOrderId($manualOrderReport);
            }
            $truckReport->setCheckedOrders($simpleArray ?? []);
            $entityManager->merge($truckReport);
        } catch (\Exception $error) {
            throw new \Exception(
                sprintf(
                    '%s, no arquivo %s e linha %s',
                    $error->getMessage(),
                    $error->getFile(),
                    $error->getLine()
                ),
                $error->getCode()
            );
        }
        $entityManager->flush();

        return new FlashResponse(
            200,
            'success',
            sprintf(
                'Relatorio %s editado com sucesso',
                $orderTruckEntity->getId()
            )
        );
    }

    /**
     * @param array $reportData Mandar parametro com as sefintes informaçãoes no devido formato
     *                          'driverName' => 'Nome do rapaz',
     *                          'kmout' => null|'236599',
     *                          'departureDate => null|'2010/10/31' Formarto YYYY/MM/DD
     * @param array $orders Array associativo contendo ids de ManualOrderReport
     * @return FlashResponse
     * @throws \Exception
     */
    public function createTruckDepartureReport(array $reportData, array $orders): FlashResponse
    {
        $entityManager = $this->entityManager;
        $repository = $entityManager->getRepository(ManualOrderReport::class);
        $truckArrivalReport = new TravelTruckOrders();
        $simpleArray = [];
        try {
            $truckArrivalReport->setDriverName($reportData['driverName']);
            $truckArrivalReport->setKmout($reportData['kmout']);
            $date = new MyDateTime($reportData['departureDate'], 'America/Belem');
            $truckArrivalReport->setDepartureDate($date);
            foreach ($orders as $order) {
                $simpleArray[$order['id']] = isset($order['isChecked']) ? (bool) $order['isChecked'] : false;
                $manualOrderReport = $repository->find($order['id']);
                $truckArrivalReport->addOrderId($manualOrderReport);
            }
            $truckArrivalReport->setCheckedOrders($simpleArray);
            $truckArrivalReport->setActive(true);
            $entityManager->persist($truckArrivalReport);
        } catch (\Exception $error) {
            throw new \Exception($error->getMessage(), $error->getCode());
        }
        $entityManager->flush();

        return new FlashResponse(
            200,
            'success',
            'Relatorio de saida do caminhão criado com sucesso!'
        );
    }

    /**
     * @param array $parameters
     * @param TravelTruckOrders $travelTruckOrder
     * @return TravelTruckOrders
     * @throws \App\Utils\Exceptions\CustomException
     */
    public function setParametersOnTruckOrder(
        array $parameters,
        TravelTruckOrders $travelTruckOrder
    ): TravelTruckOrders {
        $repository = $this->entityManager
            ->getRepository(ManualOrderReport::class);
        $travelTruckOrder->setDriverName($parameters['driverName']);
        $travelTruckOrder->setKmout($parameters['kmout']);
        $date = new MyDateTime($parameters['departureDate'], 'America/Belem');
        $travelTruckOrder->setDepartureDate($date);
        foreach ($parameters['orders'] as $order) {
            $simpleArray[$order['id']] = isset($order['isChecked']) ? (bool) $order['isChecked'] : false;
            $manualOrderReport = $repository->find($order['id']);
            $travelTruckOrder->addOrderId($manualOrderReport);
        }
        $travelTruckOrder->setCheckedOrders($simpleArray ?? []);

        return $travelTruckOrder;
    }

    /**
     * @param Collection|ManualOrderReport $collectionOfOrders
     * @return array
     */
    public function listOfProducts(Collection $collectionOfOrders): array
    {
        $listOfProducts = [];
        $modelNames = $collectionOfOrders->map(function ($order) {
            /** @var ManualOrderReport $order */
            return $order->getManualProductCarts();
        });
        foreach ($modelNames->getValues() as $value) {
            /** @var Collection $value */
            $listOfProducts[] = array_map(function ($prod) {
                /** @var ManualProductCart $prod */
                return [$prod->getProductName() => $prod->getProductAmount()];
            }, $value->getValues());
        }

        return $this->sumSameNameProd($listOfProducts);
    }

    /**
     * @param array $products
     * @return array
     */
    private function sumSameNameProd(array $products): array
    {
        $prod = $this->putAllItemsInSameLevel($products);
        $finalList = [];
        foreach ($prod as $key => $value) {
            if (array_key_exists($key, $finalList)) {
                $finalList[$key] = $finalList[$key] + $value;
                continue;
            }
            $finalList[$key] = $value;
        }

        return $finalList;
    }

    private function putAllItemsInSameLevel(array $multiLevelArray): array
    {
        foreach ($multiLevelArray as $key => $value) {
            if (!is_array($value)) {
                self::$manipulableArray[$key] = $value;
                continue;
            }
            $this->putAllItemsInSameLevel($value);
        }

        return self::$manipulableArray;
    }
}
