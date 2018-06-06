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

	public static function validate($params, array $validations)
	{
		self::$errors = '';

		foreach ($validations as $parameterName => $rule) {
			try {
				$rule->setName($parameterName)->assert($params[$parameterName]);
			} catch (NestedValidationException $e) {
				self::$errors = $e->getMessages();
			}
		}
	}

	public static function getErrors()
	{
		return self::$errors;
	}
}