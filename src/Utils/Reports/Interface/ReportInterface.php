<?php

namespace App\Utils\Reports\Interface;  

interface ReportInterface
{
    /**
     * create
     *
     * @var ARRAY $parans => array contendo os dados para criação do relatorios
     *
     */
    public function create(array $parans);

    // mostra o relatório criado
    public function show();
}
