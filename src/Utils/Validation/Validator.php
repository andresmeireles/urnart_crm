<?php

namespace App\Utils\Validation;

use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
	/**
	 * Lista de erros que podem ocorrer a um campo
	 * 
	 * @var static array
	 */
	protected $errors;

    function __construct($params, array $validations) 
    {
        $this->validate($params, $validations);
    }

	/**
	 * Recebe um array associativo com campos e validações
	 *
	 * @param [array] $params Array com nomes e validadores
	 * @param array $validations Array associativo com nome do parametro e respectivos validadores
	 * @return void
	 */
	private function validate($params, array $validations)
	{
		foreach ($validations as $parameterName => $rule) {
			try {
				$rule->setName($parameterName)->assert($params[$parameterName]);
			} catch (NestedValidationException $e) {
				$this->errors = $e->getMessages();
			}
        }
        
        if ($this->getErrors()) {
            print_r($this->errors);
            die();
        } 

        return true;
	}

	private function getErrors()
	{
		return $this->errors;
	}
}