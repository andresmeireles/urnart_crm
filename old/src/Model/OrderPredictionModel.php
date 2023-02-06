<?php

declare(strict_types=1);

namespace App\Model;

use App\Repository\OrderPredictionRepository;
use App\Utils\Andresmei\FlashResponse;
use DateTimeInterface;
use Doctrine\ORM\EntityManagerInterface;

final class OrderPredictionModel
{
    protected EntityManagerInterface $entityManager;

    protected OrderPredictionRepository $repository;

    public function __construct(
        EntityManagerInterface $entityManager,
        OrderPredictionRepository $orderPredictionRepository
    ) {
        $this->entityManager = $entityManager;
        $this->repository = $orderPredictionRepository;
    }

    public function setOrderPredictionDate(int $orderId, DateTimeInterface $date): FlashResponse
    {
        $orderToPredict = $this->repository->find($orderId);
        try {
            $orderToPredict->setPredictionDate($date);
            $this->entityManager->persist($orderToPredict);
            $this->entityManager->flush();
            return new FlashResponse(200, 'success', 'Data de previsão modificado');
        } catch (\Exception $err) {
            return new FlashResponse(200, 'error', $err->getMessage());
        }
    }

    /**
     *
     * @return \App\Entity\OrderPrediction[]
     */
    public function activePredictableOrders(): array
    {
        return $this->repository->predictedOrders();
    }

    public function populate(): void
    {
        $this->repository->populate();
    }
}
