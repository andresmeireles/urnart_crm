<?php

namespace App\Utils\Validation;

use \Respect\Validation\Exceptions\NestedValidationException;

class ValidatorJson
{
    /**
     * Lista de erros que podem ocorrer a um campo
     *
     * @var static array
     */
    protected static $errors;

    private function __construct()
    {
    }

    /**
     * Recebe um array associativo com campos e validações
     *
     * @param [array] $params Array com nomes e validadores
     * @param array $validations Array associativo com nome do parametro e respectivos validadores
     * @return void
     */
    public static function validate($params, array $validations)
    {
        foreach ($validations as $parameterName => $rule) {
            try {
                $rule->setName($parameterName)->assert($params[$parameterName]);
            } catch (NestedValidationException $err) {
                self::$errors = $err->getMessages();
            }
        }
    }

    public static function getErrors()
    {
        return self::$errors;
    }
}
