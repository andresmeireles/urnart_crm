<?php declare(strict_types = 1);

namespace App\Utils\Validation;

use Respect\Validation\Exceptions\NestedValidationException;

final class Validator
{
    /**
     * @var array
     */
    private $errors;

    public function __construct($params, array $validations)
    {
        $this->validate($params, $validations);
    }

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param array $params Array com nomes e validadores
     * @param array $validations Array associativo com nome do parametro e respectivos validadores
     * @return bool
     */
    private function validate(array $params, array $validations): bool
    {
        foreach ($validations as $parameterName => $rule) {
            try {
                $rule->setName($parameterName)->assert($params[$parameterName]);
            } catch (NestedValidationException $err) {
                $this->errors = $err->getMessages();
            }
        }

        return true;
    }
}
