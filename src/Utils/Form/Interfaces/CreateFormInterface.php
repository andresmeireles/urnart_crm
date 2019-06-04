<?php

namespace App\Utils\Form\Interfaces;

use App\Utils\Form\Parameters;

interface CreateFormInterface
{
    /**
     * Função que cria e renderiza formulário
     * @param  Parameters $parameters Objeto com parametros clonados e não clonados
     * @return ResponseFormInterface  Retorna um objeto de resposta
     */
    public function createForm(Parameters $parameters);
}
