<?php 
namespace App\Utils\Validation;

/**
 * Faz a curadoria para os campos que serão enviados para validação
 */
class ValidationFields
{
	private $parameterField;
	public $validations;

	function __construct(array $parameterField, array $validations)
	{
		$this->parameterField = $parameterField;
		$this->validations = $validations;
	}

	public function getParameter()
	{
		foreach ($this->parameterField as $key => $value) {
			if (is_array($value)) {
				$p = $value;
			}
		}
		return $this->parameterField;
	}
}