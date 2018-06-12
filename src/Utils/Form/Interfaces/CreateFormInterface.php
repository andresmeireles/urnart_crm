<?php

namespace App\Utils\Form\Interfaces;  

use App\Utils\Form\Parameters;

interface CreateFormInterface 
{
    /**
     * create
     *
     * @var ARRAY $parans => array contendo os dados para criação do relatorios
     * @var ARRAY $clonedFields => campos que tem estrutura identica e foram repetidos muitas vezes
     *
     */
    public function createForm(Parameters $parameters);

    /**
     * Return a message for user
     * @return array Array with the messages for the user
     */
    public function getMessage();
}
