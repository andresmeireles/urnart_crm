<?php
declare(strict_types=1);

namespace App\Model;

class SurveyModel implements ModelInterface
{
    private $config;

    public function __construct()
    {
        $this->config = new \App\Config\NonStaticConfig;
    }

    public function saveData(array $data): object
    {
        $surveyResultString = $this->writeResult($data['customer']);
    }

    private function writeResult(array $customerData): string 
    {
        $questionary = $this->config->getProperty('surveyQuestion');
        $resultString = '';

        return $resultString;
    }
}