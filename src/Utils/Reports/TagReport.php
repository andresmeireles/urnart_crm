<?php

namespace App\Utils\Reports;

use \App\Utils\Reports\Interfaces\ReportInterface;
use Symfony\Component\HttpFoundation\Request;
use \App\Utils\Validation\ValidatorJson;
use \Respect\Validation\Validator as v;
use \Knp\Snappy\Pdf;

class TagReport implements ReportInterface
{
	private $report;
    private $validator;
    private $error;

	function __construct()
	{
		$this->validator = new ValidatorJson();
	}

	public function create(array $params): bool
	{
		foreach ($params as $parameter) {
			if (!$this->validation($parameter)) {
				$this->error = $this->validator->getErrors();
                return false;
			}	
		}

		$image = file_get_contents(__DIR__.'/ReportTemplate/etiqueta/logo');

		$header = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=8">
		<title>Etiqueta</title>
		<meta name="generator" content="BCL easyConverter SDK 5.0.08">
		<style type="text/css">		

		body{
			margin-left: -17px;
			margin-top: -10px;
		}

		#page_1 {position:relative; overflow: hidden;margin: 0px 0px 0px 28px;padding: 0px;border: none;}

		.ft0{font: bold 47px "Arial";line-height: 55px;}
		.ft1{font: 53px "Arial";line-height: 60px;}
		.ft3{font: 23px "Arial";line-height: 26px;}
		.ft4{font: 1px "Arial";line-height: 27px;}

		.p0{text-align: right;margin-top: 5px;margin-bottom: 0px;margin-right: 37px;white-space: nowrap;}
		.p1{text-align: center;margin-left: 750px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
		.p2{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}

		.td0{padding: 0px;margin: 0px;width: 240px;vertical-align: bottom;}
		.td1{padding: 0px;margin: 0px;width: 491px;vertical-align: bottom;}
		.td2{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 240px;vertical-align: bottom;}
		.td3{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 491px;vertical-align: bottom;}

		.tr2{height: 84px;}
		.tr3{height: 27px;}

		.t0{font: bold 47px "Arial";}

		.table {
			width: 1150px;
		}

		</style>
		</head>
		<body>';

		foreach ($params as $param) {
			for ($c = 0; $c < $param['amount']; $c++) {
				$header .= '

				<table class="table">

				<tr>
				<td class="tr2 td0"><p class="p0 ft0"><img src="'. $image .'" width="40%" padding="100px"> '. $param['name'] .'</p></TD>
				<td class="tr2 td1"><p class="p1 ft1">'. ( $param['check'] == 0 ? $c+1 : ceil($c+1 / 2) ) .'</p></td>
				</tr>
				<tr>
				<td class="tr3 td2"><p class="p2 ft4">&nbsp;</p></td>
				<td class="tr3 td3"><p class="p1 ft3">VOL. '. ($param['check'] == 0 ? $param['amount'] : ceil($param['amount']/2)) .'</p></td>
				</tr>

				</table>';
			}
		}

		$header .= '</BODY>
		</HTML>';

		$this->report = $header;

		return true;
	}


	private function validation(array $params)
	{
		$this->validator->validate($params, [
			'name' => v::notEmpty(),
			'city' => v::optional(v::notEmpty()),
			'amount' => v::not(v::negative())->notEmpty(),
			'check' => v::optional(v::boolVal())
		]);

		if ($this->validator->getErrors()) {
			return false;
		}

		return true;
	}

	public function show()
	{
		$pdf = new Pdf(__DIR__.'\..\..\..\vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf.exe');

		header('Content-Type: application/pdf');
		echo $pdf->getOutputFromHtml($this->report);
	}

	public function getMessage(): array
	{
		return $this->error;
	}
}
