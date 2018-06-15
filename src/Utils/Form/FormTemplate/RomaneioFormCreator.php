<?php

namespace App\Utils\Form\FormTemplate;

use App\Utils\Form\Interfaces\CreateFormInterface;
use App\Utils\Form\Interfaces\ResponseFormInterface;
use App\Utils\Form\Parameters;

class RomaneioFormCreator implements CreateFormInterface
{
	public function createForm(Parameters $parameters): ResponseFormInterface
	{
		print_r($parameters->getNonClonedParameters()['type']);
		die();

		ValidatorJson::validate($parameters->getNonClonedParameters(), [
			'type' => v::notEmpty()->boolVal()
		]);

		foreach ($parameters->getClonedParameters() as $paraemters) {
			ValidatorJson::validate($parameters, [
				'name' => v::notEmpty()->not(v::numeric()),
				'city' => v::notEmpty()->not(v::numeric()),
				'urnG' => v::notEmpty()->not(v::nullType()->stringType()),
				'urnM' => v::notEmpty()->not(v::nullType()->stringType()),
				'urnP' => v::notEmpty()->not(v::nullType()->stringType()),
				'type' => v::notEmpty()->not(v::nullType()->stringType()),
				'freight' => v::notEmpty()->positive()->not(v::nullType()->stringType()->negative())
			]);
		}

		if (ValidatorJson::getErrors()) {
			$this->getMessage();
		}

		if ($parameters->getNonClonedParameters()['type'] == 0) {
			$response = $this->createTravelRomaneio();
			return new ResponseForm($response);
		}

		$response = $this->createBoardingRomaneio();
		return new ResponseForm($response);
	}

	public function getMessage()
	{
		return ValidatorJson::getErrors();
	}

	private function createBoradingRomaneio(string $parameters): string
	{

	}

	private function createTravelRomaneio(string $parameters): string
	{
		
	}
}
