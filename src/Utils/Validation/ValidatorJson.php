<?php

namespace App\Utils\Validation;

use \Respect\Validation\Exceptions\NestedValidationException;

class ValidatorJson
{
	/**
	 * Lista de erros que podem ocorrer a um campo
	 * 
	 * @var array
	 */
	protected $errors;

	public function validate($params, array $validations)
	{
		$this->errors = '';

		foreach ($validations as $parameterName => $rule) {
			try {
				$rule->setName($parameterName)->assert($params[$parameterName]);
			} catch (NestedValidationException $e) {
				$this->errors = $e->getMessages();
			}
		}
	}

	public function getErrors()
	{
		return $this->errors;
	}
}