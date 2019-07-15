<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\TravelEntry;

/**
 * Class TravelEntryModel
 * @package App\Model
 */
final class TravelEntryModel extends Model
{
    /**
     * @param array $travelEntryInformation
     * @return TravelEntry
     * @throws \Exception
     */
    public function create(array $travelEntryInformation): TravelEntry
    {
        try {
            return $this->createTravelEntry($travelEntryInformation);
        } catch (\PDOException $err) {
            throw new \RuntimeException($err->getMessage());
        }
    }

    private function createTravelEntry(array $travelEntryInformation): TravelEntry
    {
        $entityManager = $this->entityManager;
        $travelEntryEntity = new TravelEntry();
        $travelEntryEntity->setNome($travelEntryInformation['customer']);
        $travelEntryEntity->setByCountValue((float) $travelEntryInformation['other']);
        $travelEntryEntity->setCheckValue((float) $travelEntryInformation['check']);
        $travelEntryEntity->setFreight((float) $travelEntryInformation['freight']);
        $travelEntryEntity->setOrderValue((float) $travelEntryInformation['order-value']);
        $entityManager->persist($travelEntryEntity);
//        $entityManager->flush();

        return $travelEntryEntity;
    }

    public function removeAllOccurrencesByAccountabilityId(int $accountabilityId): void
    {
        try {
            $queryConsultString = sprintf(
                'DELETE FROM %s t WHERE  t.orderReference = %s',
                TravelEntry::class,
                $accountabilityId
            );
            $this->dqlQuery($queryConsultString);
        } catch (\PDOException $err) {
            throw new \PDOException($err->getMessage());
        }
    }

}