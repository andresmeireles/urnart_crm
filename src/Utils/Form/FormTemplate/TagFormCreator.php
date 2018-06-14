<?php

namespace App\Utils\Form\FormTemplate;

use \App\Utils\Form\ResponseForm;
use App\Utils\Form\Parameters;
use \App\Utils\Form\Interfaces\ResponseFormInterface;
use \App\Utils\Form\Interfaces\CreateFormInterface;
use \App\Utils\Validation\ValidatorJson;
use \Respect\Validation\Validator as v;

class TagFormCreator implements CreateFormInterface
{
	/**
	 * Storage the error messages, if exists
	 * @var array
	 */
	private $error;

	public function createForm(Parameters $params): ResponseFormInterface 
	{
		$parameters = $params->getClonedParameters();

		$response = new ResponseForm();

		$image = file_get_contents(__DIR__.'\etiqueta/logo');

		$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
		<HTML>
		<HEAD>
		<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<META http-equiv="X-UA-Compatible" content="IE=8">
		<TITLE>Etiquetas</TITLE>
		<META name="generator" content="BCL easyConverter SDK 5.0.08">
		<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", function () {
			window.print();
			});
			</script>
		<STYLE type="text/css">

		body {margin-top: 0px;margin-left: 0px;}

		#page_1 {position:relative; overflow: hidden;margin: 0px 0px 0px 0px;padding: 0px;border: none;width: 762px;}
		#page_1 #id_1 {border:none;margin: 0px 0px 0px 0px;padding: 0px;border:none;width: 730px;overflow: hidden;}

		.ft0{font: bold 31px "Arial";line-height: 55px;}
		.ft1{font: 53px "Arial";line-height: 60px;}
		.ft2{font: 1px "Arial";line-height: 28px;}
		.ft3{font: 23px "Arial";line-height: 26px;}
		.ft4{font: 1px "Arial";line-height: 27px;}
		.ft5{font: bold 54px "Arial";line-height: 55px;}

		.p0{text-align: left;margin-top: 0px;margin-bottom: 0px;}
		.p1{text-align: right;padding-left: 30px;margin-top: 0px;margin-bottom: 0px;}
		.p2{text-align: left;margin-top: 0px;margin-bottom: 0px;}
		.p3{text-align: left;margin-top: 0px;margin-bottom: 0px;}

		.td0{padding: 0px;margin: 0px;vertical-align: bottom;}
		.td1{padding: 0px;margin: 0px;vertical-align: bottom;}
		.td2{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;vertical-align: bottom;}
		.td3{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;vertical-align: bottom;}
		.td5{padding: 0px;margin: 0px;width: 120px;vertical-align: bottom;}

		.tr0{height: 70px;}
		.tr1{height: 30px;}

		.t0{width: 730px;font: bold 47px "Arial";}

		</STYLE>
		</HEAD>
		<BODY>
		<DIV id="page_1">
		<DIV id="id_1">
		<TABLE cellpadding=0 cellspacing=0 class="t0">';

		foreach ($parameters as $param) {
			if (!$this->validation($param)) {
				return $response->setErrorMessage(ValidatorJson::getMessage());     
			}

			for ($c = 0; $c < $param['amount']; $c++) {
				$count = $c;
				$count++;
				$body .= '<TR>
				<td class="tr0 td5"><img src="'. $image .'" width="90px"></td>
				<TD class="tr0 td0"><P class="p0 '. (($param['city'] != "") ? 'ft0' : "ft5") .'">'. $param['name'] .'</P></TD>
				<TD class="tr0 td1"><P class="p1 ft1">'. ( $param['check'] == 0 ? $count : ceil($count/2) ) .'</P></TD>
				</TR>
				<TR>
				<TD class="tr1 td2"><P class="p2 ft2">&nbsp;</P></TD>
				<TD class="tr1 td2"><P class="p2 ft3">'. $param['city'] .'</P></TD>
				<TD class="tr1 td3"><P class="p1 ft3">VOL. '. ($param['check'] == 0 ? $param['amount'] : ceil($param['amount']/2)) .'</P></TD>
				</TR>';
			}
		}

		$body .= '</TABLE>
		</DIV>
		</DIV>
		</BODY>
		</HTML>';

		$p = $response->setResponse($body);
		return $p;
	}

	private function validation(array $params): bool
	{
		ValidatorJson::validate($params, [
			'name' => v::notEmpty(),
			'city' => v::optional(v::notEmpty()),
			'amount' => v::not(v::negative())->notEmpty(),
			'check' => v::optional(v::boolVal())
		]);

		if (ValidatorJson::getErrors()) {
			return false;
		}

		return true;
	}

	public function getMessage()
	{

	}
}
