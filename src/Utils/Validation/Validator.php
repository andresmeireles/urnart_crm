<?php

namespace App\Utils\Validation;

use Respect\Validation\Exceptions\NestedValidationException;

final class Validator
{
    /**
     * Lista de erros que podem ocorrer a um campo
     *
     * @var array
     */
    protected $errors;

    public function __construct($params, array $validations)
    {
        $this->validate($params, $validations);
    }

    /**
     * Recebe um array associativo com campos e validações
     *
     * @param [array] $params Array com nomes e validadores
     * @param array $validations Array associativo com nome do parametro e respectivos validadores
     * @return bool
     */
    private function validate($params, array $validations): bool
    {
        foreach ($validations as $parameterName => $rule) {
            try {
                $rule->setName($parameterName)->assert($params[$parameterName]);
            } catch (NestedValidationException $err) {
                $this->errors = $err->getMessages();
            }
        }
        if ($this->getErrors()) {
            print_r($this->errors);
            die;
        }

        return true;
    }

    private function getErrors(): array
    {
        return $this->errors;
    }
}
