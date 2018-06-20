<?php 

namespace App\Utils\Form\FormTemplate;

use App\Utils\Form\Interfaces\CreateFormInterface;
use App\Utils\Form\Interfaces\ResponseFormInterface;
use App\Utils\Form\ResponseForm;
use App\Utils\Form\Form;
use App\Utils\Form\Parameters;
use App\Utils\Validation\ValidatorJson;
use Respect\Validation\Validator as v;

class RemandFormCreator implements CreateFormInterface
{
	public function createForm(Parameters $parameters): ResponseFormInterface 
	{

	}

	public function getMessage()
	{

	}

	private function chequebody(array $parameters): string
	{

	}

	private function productbody(Parameters $parameters): string 
	{

	}
}
