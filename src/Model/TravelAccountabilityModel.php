<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\Expenses;
use App\Entity\TravelAccountability;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\NestedArraySeparator;

/**
 * Class TravelAccountabilityModel
 * @package App\Model
 */
final class TravelAccountabilityModel extends Model
{
    /**
     * @param array $accountabilityInfo
     */
    public function createTravelAccountability(array $accountabilityInfo): void
    {
        $this->travelAccountabilityAction(new TravelAccountability(), $accountabilityInfo);
    }

    /**
     * @param int $travelAccountabilityId
     * @param array $accountabilityInfo
     */
    public function editTravelAccountability(int $travelAccountabilityId, array $accountabilityInfo): void
    {
        $entityManager = $this->entityManager;
        $expenseModel = new ExpensesModel($entityManager);
        $expenseModel->removeAllExpensesByAccountabilityId($travelAccountabilityId);
        $travelEntryModel = new TravelEntryModel($entityManager);
        $travelEntryModel->removeAllOccurencesByAccountabilityId($travelAccountabilityId);
        $travelAccountabilityObj = $entityManager->getRepository(TravelAccountability::class)
            ->find($travelAccountabilityId);
        $this->travelAccountabilityAction($travelAccountabilityObj, $accountabilityInfo);
    }

    /**
     * @param array $accountabilityInfo
     * @return FlashResponse
     */
    private function travelAccountabilityAction(
        TravelAccountability $travelAccountability,
        array $accountabilityInfor
    ): FlashResponse {
        try {
            $this->createAccountabilityReportWithData($travelAccountability, $accountabilityInfor);
        } catch (\PDOException $err) {
            throw new \PDOException($err->getMessage());
        }

        return new FlashResponse(200, 'success', 'relatorio salvo com sucesso.');
    }

    private function createAccountabilityReportWithData(TravelAccountability $travelAccountability, array $data): void
    {
        $entityManager = $this->entityManager;
        $nestedArray = new NestedArraySeparator($data);
        array_map(function ($expense) use (&$entityManager, &$travelAccountability) {
            $expenseModel = new ExpensesModel($entityManager);
            $expense['insertId'] = $travelAccountability;
            $insertedExpense = $expenseModel->create($expense);
            $travelAccountability->addExpense($insertedExpense);
        }, $nestedArray->getAssoativeArrayGroup('despesas'));
        array_map(function ($entry) use (&$entityManager, &$travelAccountability) {
            $travelEntryModel = new TravelEntryModel($entityManager);
            $insertedTravelEntry = $travelEntryModel->create($entry);
            $travelAccountability->addTravelEntry($insertedTravelEntry);
        }, $nestedArray->getAssoativeArrayGroup('customerArr'));
        $travelAccountability->setDriverName($data['driverName']);
        $travelAccountability->setArrivalDate(new \DateTime($data['arrivalDate']));
        $travelAccountability->setDepartureDate(new \DateTime($data['departureDate']));
        $travelAccountability->setArrivalKm((float) $data['arrivalKm']);
        $travelAccountability->setDepartureKm((float) $data['departureKm']);
        $travelAccountability->setCash((float) $data['cash']);
        $travelAccountability->setComment($data['comment']);
        $entityManager->persist($travelAccountability);
        $entityManager->flush();
    }
}