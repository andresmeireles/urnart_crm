<?php

namespace App\Model;

interface ModelInterface
{
    public function saveData(array $data, string $customerId, string $surveyReferenceDate): object;   

    public function createRegistry(array $data, string $date, string $type): array;
}