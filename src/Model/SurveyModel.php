<?php
declare(strict_types = 1);

namespace App\Model;

use App\Entity\Survey;
use App\Utils\Andresmei\ObjectResponse;

class SurveyModel extends Model implements ModelInterface
{
    /**
     * Create a registry in database
     *
     * @param array  $data
     * @param string $date
     * @param string $type
     * @return array
     */
    public function createRegistry(array $data, string $date, string $type): array
    {
        $entityManager = $this->em->getManager();
        $connection = $this->em->getConnection();
        try {
            $connection->beginTransaction();
            foreach ($data as $value) {
                $survey = new Survey();
                $survey->setCustomerName($value['name']);
                $survey->setSurveyType($type);
                $survey->setSurveyReferenceDay($date);
                $entityManager->persist($survey);
                $entityManager->flush();
            }
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            throw new \Exception(sprintf("Error: %s", $e->getMessage()));
        }
        return ['msg' => 'Sucesso!'];
    }

    public function saveData(array $data, string $customerId, string $surveyReferenceDate): ObjectResponse
    {
        $surveyResultString = $this->writeResult($data['customer']);
        $entityManager = $this->em->getManager();
        $connection = $this->em->getConnection();
        $connection->beginTransaction();
        try {
            $userSurvey = $entityManager->getRepository(Survey::class)->findOneBy([
                'id' => $customerId,
                'surveyReferenceDate' => $surveyReferenceDate
            ]);
            $userSurvey->setAnswer($surveyResultString);
            $entityManager->merge($userSurvey);
            $entityManager->flush();
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollback();
            throw new \Exception($e->getMessage());
        }

        return new ObjectResponse();
    }

    /**
     * Recieve assoeiative array with data, return results by dates
     *
     * @param array $surveyBruteData
     *
     * @return array
     */
    public function getSurveyData(array $surveyBruteData): array
    {
        $cleanSurveyData = [];
        foreach ($surveyBruteData as $value) {
            if (!array_key_exists($value->getSurveyReferenceDate(), $cleanSurveyData)) {
                $cleanSurveyData[$value->getSurveyReferenceDate()] = [];
            }

            $cleanSurveyData[$value->getSurveyReferenceDate()] += [
                $value->getCustomerName() => [
                    'id' => $value->getId(),
                    'answer' => $value->getAnswer()
                ]
            ];
        }

        return $cleanSurveyData;
    }

    private function writeResult(array $customerData): string
    {
        $questionary = $this->settings->getProperty('survey_question');
        $resultString = [];
        foreach ($customerData as $key => $value) {
            $text = $questionary[$key]['text'];
            $resultString[$text] = $value;
        }
        return (string) json_encode($resultString);
    }
}
