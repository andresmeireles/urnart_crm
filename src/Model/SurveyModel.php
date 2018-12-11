<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Survey;

class SurveyModel extends Model implements ModelInterface
{
    public function saveData(array $data): object
    {
        $surveyResultString = $this->writeResult($data['customer']);
        $entityManager = $this->em->getManager();
        $connection = $this->em->getConnection();
        $connection->beginTransaction();
        try {
            //code...
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            throw new \Exception($e->getMessage());
        }
    }

    private function writeResult(array $customerData): string 
    {
        $questionary = $this->settings->getProperty('surveyQuestion');

        $resultString = '';

        return $resultString;
    }
}