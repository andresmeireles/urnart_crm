<?php

namespace App\Utils\Reports\Interfaces;  

interface CreateReportInterface 
{
    /**
     * create
     *
     * @var ARRAY $parans => array contendo os dados para criação do relatorios
     *
     */
    public function createReport(array $parans);

    /**
     * Return a message for user
     * @return array Array with the messages for the user
     */
    public function getMessage();
}
