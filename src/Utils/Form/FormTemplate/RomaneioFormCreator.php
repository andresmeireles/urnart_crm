<?php

namespace App\Utils\Form\FormTemplate;

use App\Utils\Form\Interfaces\CreateFormInterface;
use App\Utils\Form\Interfaces\ResponseFormInterface;
use App\Utils\Form\Parameters;

class RomaneioFormCreator implements CreateFormInterface
{
	public function createForm(Parameters $parameters): ResponseFormInterface
	{
		$clonedParameters = $parameters->getClonedParameters();

		ValidatorJson::validate($parameters->getNonClonedParameters(), [
			'type' => v::notEmpty()->boolVal()
		]);

		foreach ($clonedParameters as $paraemters) {
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
			$response = $this->createTravelRomaneio($clonedParameters);
			return new ResponseForm($response);
		}

		$response = $this->createBoardingRomaneio($clonedParameters);
		return new ResponseForm($response);
	}

	public function getMessage()
	{
		return ValidatorJson::getErrors();
	}

	private function createBoradingRomaneio(array $parameters): string
	{
		$body = '<!DOCTYPE html>
		<html>
		<head>
		<title>Romaneio</title>
		</head>
		<style type="text/css">
		body {margin-top: 0px;margin-left: 0px;}

		#page_1 {position:relative; overflow: hidden;margin: 114px 0px 446px 89px;padding: 0px;border: none;width: 1034px;}

		.t0{width: 922px;margin-top: 6px;font: 17px "Calibri"; border: 1px solid black}

		.head { border-bottom: 1px solid black; text-align: center; padding: 2px 0 0 3px; font-size: 20px;}
		.head2 span { margin: 0 90px 0 0; font-size: 20px}
		.head1 td { border-bottom: 1px solid black; }

		.hbody td {
			font-weight: bold;
			font-size: 22px;
			border-right: 1px solid black;
			border-bottom: 1px solid black;
		}

		.body td { border-right: 1px solid black; border-bottom: 1px solid black; }

		.space td { border-bottom: 1px solid black; }

		.foot td { border-right: 1px solid black}

		.no-border { border-right: 1px solid white !important }
		.lp { padding: 0 0 0 2px;}
		.center { text-align:center }
		</style>
		<body>
		<div id="page_1">

		<table cellpadding=0 cellspacing=0 class="t0">
		<tr>
		<td colspan=10 class="head">Romaneio de Saida</td>
		</tr>
		<tr class="head1">
		<td colspan=10 class="head2">
		<span>Data Saida?</span>
		<span>KM:</span>
		<span>Hora:</span>
		<span>Dt Chegada:</span>
		<span>KM:</span>
		<span>Hora:</span>
		</td>
		</tr>
		<tr class="hbody">
		<td width="3%" class="lp">N°</td>
		<td width="30%" class="lp">Cliente</td>
		<td width="22%" class="lp">Cidade</td>
		<td colspan=3 class="center" width="15%">Urnas</td>
		<td width="6%" class="center">Total</td>
		<td width="11%" class="center">Forma Pg.</td>
		<td colspan=2 class="lp no-border">Frete</td>
		</tr>';

		foreach ($cloned as $param) {
			$body = '<tr class="body">
			<td class="lp">1</td>
			<td class="lp">Andre</td>
			<td class="lp">Belém</td>
			<td class="center">1</td>
			<td class="center">1</td>
			<td class="center">1</td>
			<td class="center">3</td>
			<td class="center">Buleto</td>
			<td class="lp no-border">R$</td>
			<td class="no-border">10,00</td>
			</tr>';

			$totalFreight = 0;
			$totalP = 0;
			$totalM = 0;
			$totalG = 0;
			$total = 0;
		}		

		$body .= '<tr class="space">
		<td>&nbsp;</td>
		<td colspan=9>&nbsp;</td>
		</tr>
		<tr class="foot">
		<td colspan=3>&nbsp;</td>
		<td class="center">'. $totalG .'</td>
		<td class="center">'. $totalM .'</td>
		<td class="center">'. $totalP .'</td>
		<td class="center">'. $total .'</td>
		<td>&nbsp;</td>
		<td class="lp no-border">R$</td>
		<td class="no-border">'. $totalFreight .',00</td>
		</tr>
		</table>
		
		</div>
		</body>
		</html>';
	}

	private function createTravelRomaneio(string $parameters): string
	{
		
	}
}
