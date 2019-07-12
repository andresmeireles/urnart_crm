<?php declare(strict_types = 1);

namespace App\Model;

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
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function createTravelAccountability(array $accountabilityInfo): void
    {
        $this->travelAccountabilityAction(new TravelAccountability(), $accountabilityInfo);
    }

    /**
     * @param int $travelAccountabilityId
     * @param array $accountabilityInfo
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    public function editTravelAccountability(int $travelAccountabilityId, array $accountabilityInfo): void
    {
        $entityManager = $this->entityManager;
        $expenseModel = new ExpensesModel($entityManager, $this->getValidator());
        $expenseModel->removeAllExpensesByAccountabilityId($travelAccountabilityId);
        $travelEntryModel = new TravelEntryModel($entityManager, $this->getValidator());
        $travelEntryModel->removeAllOccurencesByAccountabilityId($travelAccountabilityId);
        $travelAccountabilityObj = $entityManager->getRepository(TravelAccountability::class)
            ->find($travelAccountabilityId);
        $this->travelAccountabilityAction($travelAccountabilityObj, $accountabilityInfo);
    }

    /**
     * @param TravelAccountability $travelAccountability
     * @param array $accountabilityInfor
     * @return FlashResponse
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \JsonException
     * @throws \ReflectionException
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

    /**
     * @param TravelAccountability $travelAccountability
     * @param array $data
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \JsonException
     * @throws \ReflectionException
     */
    private function createAccountabilityReportWithData(
        TravelAccountability $travelAccountability,
        array $data
    ): void {
        $entityManager = $this->entityManager;
        $error = [];
        $validator = $this->getValidator();
        $nestedArray = new NestedArraySeparator($data);
        array_map(function ($expense) use (&$entityManager, &$travelAccountability, &$validator, &$error) {
            if ($expense['name'] !== '') {
                $expenseModel = new ExpensesModel($entityManager, $validator);
                $insertedExpense = $expenseModel->create($expense);
                $travelAccountability->addExpense($insertedExpense);
                $error[] = $validator->executeClassValidation($insertedExpense);
            }
        }, $nestedArray->getAssoativeArrayGroup('despesas'));
        array_map(function ($entry) use (&$entityManager, &$travelAccountability, &$validator, &$error) {
            if ($entry['customer'] !== '') {
                $travelEntryModel = new TravelEntryModel($entityManager, $validator);
                $insertedTravelEntry = $travelEntryModel->create($entry);
                $travelAccountability->addTravelEntry($insertedTravelEntry);
                $error[] = $validator->executeClassValidation($insertedTravelEntry);
            }
        }, $nestedArray->getAssoativeArrayGroup('customerArr'));
        $travelAccountability->setDriverName($data['driverName']);
        $travelAccountability->setArrivalDate(new \DateTime($data['arrivalDate']));
        $travelAccountability->setDepartureDate(new \DateTime($data['departureDate']));
        $travelAccountability->setArrivalKm((float) $data['arrivalKm']);
        $travelAccountability->setDepartureKm((float) $data['departureKm']);
        $travelAccountability->setCash((float) $data['cash']);
        $travelAccountability->setComment($data['comment']);
        $entityManager->persist($travelAccountability);
        $error = $validator->executeClassValidation($travelAccountability);
        $this->sendErrorsIfExists($error);
        $entityManager->flush();
    }

    /**
     * @param array|null $error
     * @throws \JsonException
     */
    public function sendErrorsIfExists(?array $error): void
    {
        if ($error !== null) {
            $messages = '';
            foreach (array_values($error) as $value) {
                $messages .= $value[0] . ' ';
            }

            throw new \JsonException($messages);
        }
    }
}