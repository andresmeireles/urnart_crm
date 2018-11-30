<?php

namespace App\Utils\Form\FormTemplate;

use App\Utils\Form\Interfaces\CreateFormInterface;
use App\Utils\Form\Interfaces\ResponseFormInterface;
use App\Utils\Form\Parameters;
use App\Utils\Form\ResponseForm;
use App\Utils\Validation\ValidatorJson;
use Respect\Validation\Validator as v;

class RomaneioFormCreator implements CreateFormInterface
{
    public function createForm(Parameters $parameters): ResponseFormInterface
    {
        $clonedParameters = $parameters->getClonedParameters();

        ValidatorJson::validate($parameters->getNonClonedParameters(), [
            'type' => v::notEmpty()
        ]);

        foreach ($clonedParameters as $parameter) {
            ValidatorJson::validate($parameter, [
                'name' => v::stringType()->notEmpty(),
                'city' => v::stringType()->notEmpty()->not(v::numeric()),
                'urnG' => v::alnum(),
                'urnM' => v::alnum(),
                'urnP' => v::alnum(),
                'type' => v::notEmpty(),
                'freight' => v::notEmpty()->not(v::nullType()->stringType()),
            ]);
        }

        if ($parameters->getNonClonedParameters()['type'] == 2) {
            $response = $this->createTravelRomaneio($clonedParameters);
            return new ResponseForm($response, 'Landscape');
        }

        $response = $this->createBoardRomaneio($clonedParameters);
        return new ResponseForm($response, 'Landscape');
    }

    public function getMessage()
    {
        return ValidatorJson::getErrors();
    }

    private function createTravelRomaneio(array $parameters): string
    {
        $number = 1;
        $totalFreight = 0;
        $totalG = 0;
        $totalM = 0;
        $totalP = 0;
        $total = 0;

        $body = '
		<style >
		#page_1 { width: 100%; height: 100%; }

		.t0{width: 100% ;margin-top: 6px;font: 17px "Calibri"; border: 1px solid black}

		.head { border-bottom: 1px solid black; text-align: center; padding: 2px 0 0 3px; font-size: 20px;}
		.head2 span { margin: 0 80px 0 0; font-size: 20px}
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

		<div id="page_1">

		<table cellpadding=0 cellspacing=0 class="t0">
		<tr>
		<td colspan=10 class="head">Romaneio de Saida</td>
		</tr>
		<tr class="head1">
		<td colspan=10 class="head2">
		<span>Data Saida:</span>
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

        foreach ($parameters as $param) {
            $body .= '<tr class="body">
			<td class="lp">'. $number .'</td>
			<td class="lp">'. $param['name'] .'</td>
			<td class="lp">'. $param['city'] .'</td>
			<td class="center">'. $param['urnG'] .'</td>
			<td class="center">'. $param['urnM'] .'</td>
			<td class="center">'. $param['urnP'] .'</td>
			<td class="center">'. ($param['urnG'] + $param['urnM'] + $param['urnP']) .'</td>
			<td class="center">'. $param['type'] .'</td>
			<td class="lp no-border" width="3%">R$</td>
			<td class="no-border">'. $param['freight'] .'</td>
			</tr>';

            $number ++;

            if (is_numeric($param['freight'])) {
                $totalFreight += $param['freight'];
            }

            $totalP += $param['urnP'];
            $totalM += $param['urnM'];
            $totalG += $param['urnG'];
            $total += ($param['urnG'] + $param['urnM'] + $param['urnP']);
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
		<td class="no-border">'. $totalFreight .'</td>
		</tr>
		</table>
		
		</div>';

        return $body;
    }

    private function createBoardRomaneio(array $parameters): string
    {
        $number = 1;
        $totalFreight = 0;
        $totalG = 0;
        $totalM = 0;
        $totalP = 0;
        $total = 0;

        $body = '<!DOCTYPE html>
		<html>
		<head>
		<title>Romaneio</title>
		</head>
		<style type="text/css">
		@page{size:landscape}

		body {margin-top: 0px;margin-left: 0px;}

		#page_1 {position:relative; overflow: hidden;margin: 114px 0px 446px 89px;padding: 0px;border: none;width: 1034px;}

		.t0{width: 922px;margin-top: 6px;font: 17px "Calibri"; border: 1px solid black}

		.head { border-bottom: 1px solid black; text-align: center; padding: 2px 0 0 3px; font-size: 20px;}
		.head2 span { margin: 0 80px 0 0; font-size: 20px}
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
		<td colspan=10 class="head">Romaneio de Carregamento</td>
		</tr>
		<tr class="hbody">
		<td width="3%" class="lp">N°</td>
		<td width="30%" class="lp">Cliente</td>
		<td width="22%" class="lp">Cidade</td>
		<td colspan=3 class="center" width="15%">Urnas</td>
		<td width="6%" class="center no-border">Total</td>
		</tr>';

        foreach ($parameters as $param) {
            $body .= '<tr class="body">
			<td class="lp">'. $number .'</td>
			<td class="lp">'. $param['name'] .'</td>
			<td class="lp">'. $param['city'] .'</td>
			<td class="center">'. $param['urnG'] .'</td>
			<td class="center">'. $param['urnM'] .'</td>
			<td class="center">'. $param['urnP'] .'</td>
			<td class="center no-border">'. ($param['urnG'] + $param['urnM'] + $param['urnP']) .'</td>
			</tr>';

            $number ++;
            $totalP += $param['urnP'];
            $totalM += $param['urnM'];
            $totalG += $param['urnG'];
            $total += ($param['urnG'] + $param['urnM'] + $param['urnP']);
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
		<td class="center no-border">'. $total .'</td>
		</tr>
		</table>
		
		</div>
		</body>
		</html>';

        return $body;
    }
}
