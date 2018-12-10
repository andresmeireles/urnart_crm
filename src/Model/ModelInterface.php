<?php

namespace App\Model;

interface ModelInterface
{
    public function saveData(array $data): object;   
}