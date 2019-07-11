<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\ManualOrderReport;
use App\Entity\TravelTruckOrders;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\MyDateTime;

/**
 * Class TravelTruckOrderMode
 * @package App\Model
 */
final class TravelTruckOrderModel extends Model
{
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
    ) {
        $manualOrderReportRepository = $this->entityManager
            ->getRepository(ManualOrderReport::class);
        $travelTruckOrder->setDriverName($parameters['driverName']);
        $travelTruckOrder->setKmout($parameters['kmout']);
        $date = new MyDateTime($parameters['departureDate'], 'America/Belem');
        $travelTruckOrder->setDepartureDate($date);
        foreach ($parameters['orders'] as $order) {
            $simpleArray[$order['id']] = isset($order['isChecked']) ? (bool) $order['isChecked'] : false;
            $manualOrderReport = $manualOrderReportRepository->find($order['id']);
            $travelTruckOrder->addOrderId($manualOrderReport);
        }
        $travelTruckOrder->setCheckedOrders($simpleArray ?? []);

        return $travelTruckOrder;
    }

    /**
     * @param TravelTruckOrders $order
     * @return TravelTruckOrders
     */
    public function closeActiveTravelTruckOrder(
        TravelTruckOrders $order
    ): TravelTruckOrders {
        try {
            $order->setActive(false);
        } catch (\RuntimeException $err) {
            throw new \RuntimeException(
                sprintf(
                    '%s. Arquivo: %s. Linha: %s',
                    $err->getMessage(),
                    $err->getFile(),
                    $err->getLine()
                )
            );
        }

        return $order;
    }

    public function actionInTruckOrderByMode(?string $mode = null, ?int $truckOrderId)
    {
        $entity = $mode === 'edit' ?
            $this->entityManager->getRepository(TravelTruckOrders::class)->find($truckOrderId) :
            true ;
    }
}
