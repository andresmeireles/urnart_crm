<?php

namespace App\Utils\Validation;

use Respect\Validation\Exceptions\NestedValidationException;

final class ValidatorJson
{
    /**
     * @var static array
     */
    protected static $errors;

    private function __construct(){}

    /**
     * @param array $params
     * @param array $validations
     * @return void
     */
    public static function validate(array $params, array $validations): void
    {
        foreach ($validations as $parameterName => $rule) {
            try {
                $rule->setName($parameterName)->assert($params[$parameterName]);
            } catch (NestedValidationException $err) {
                self::$errors = $err->getMessages();
            }
        }
    }

    public static function getErrors(): string
    {
        return self::$errors;
    }
}
