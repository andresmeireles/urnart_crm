<?php

namespace App\Utils\Reports\Interfaces;  

interface ReportInterface
{
    /**
     * create
     *
     * @var ARRAY $parans => array contendo os dados para criação do relatorios
     *
     */
    public function create(array $parans);

    /**
     * Salva o relátorio criado em uma pasta
     * 
     * @return self retorna uma instancia do objeto
     */
    public function save();

    // mostra o relatório criado
    public function show();

    /**
     * Return a message for user
     * @return array Array with the messages for the user
     */
    public function getMessage();
}
