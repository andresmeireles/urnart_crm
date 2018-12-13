<?php

namespace App\Model;

use App\Utils\Andresmei\ObjectResponse;


interface ModelInterface
{
    public function saveData(array $data, string $customerId, string $surveyReferenceDate): ObjectResponse;   

    public function createRegistry(array $data, string $date, string $type): array;
}