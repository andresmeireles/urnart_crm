<?php

namespace App\Utils\Reports\ReportTemplate;

use \App\Utils\Reports\ResponseReport;
use \App\Utils\Reports\Interfaces\ResponseReportInterface;
use \App\Utils\Reports\Interfaces\CreateReportInterface;
use \App\Utils\Validation\ValidatorJson;
use \Respect\Validation\Validator as v;

class TagReportCreator implements CreateReportInterface
{
	/**
	 * Storage the html report
	 * @var string
	 */
	private $report;

	/**
	 * Storage the error messages, if exists
	 * @var array
	 */
	private $error;

	public function createReport(array $params): ResponseReportInterface 
    {
        $response = new ResponseReport();


        if (!$this->validation($params)) {
            return $response->setErrorMessage(ValidatorJson::getMessage());     
        }

		$image = file_get_contents(__DIR__.'\etiqueta/logo');

		$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=8">
		<title>Etiqueta</title>
		<meta name="generator" content="BCL easyConverter SDK 5.0.08">
		<style type="text/css">		

		body {margin-top: 0px;margin-left: 0px;}

		#page_1 {position:relative; overflow: hidden;margin: 0px 0px 0px 0px;padding: 0px;border: none;width: 100%;}

		.ft0{font: bold 43px "Arial";line-height: 55px;}
		.ft1{font: 39px "Arial";line-height: 60px;}
		.ft3{font: 18px "Arial";line-height: 26px;}
		.ft4{font: 24px "Arial";line-height: 27px;}

		.p0{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
		.p1{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
		.p2{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
		.p2{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
		.p5{text-align: left;margin-top: 0px;margin-bottom: 0px;margin-left: 180px;white-space: nowrap;}
		.p6{text-align: center;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}

		.td0{padding: 0px;margin: 0px;width: 240px;vertical-align: bottom;}
		.td1{padding: 0px;margin: 0px;width: 491px;vertical-align: bottom;}
		.td2{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 240px;vertical-align: bottom;}
		.td3{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 491px;vertical-align: bottom;}

		

		.border {
			border-bottom: #000000 1px solid;
		}

		.t0{width: 731px;font: bold 47px "Arial";}

		</style>
		</head>
		<body> <div id="page_1">';

			for ($c = 0; $c < $params['amount']; $c++) {
				$body .= '
				<table width="100%">

				<TR>
				<TD ><P class="p1 ft0"><img src="'. $image .'" width="160px"> '. $params['name'] .'</P></TD>
				<TD ><P class="p6 ft1">'. ( $params['check'] == 0 ? $c+1 : ceil($c+1 / 2) ) .'</P></TD>
				</TR>
				<TR>
				<TD class="border"><P class="p5 ft4">'. $params['city'] .'</P></TD>
				<TD class="border"><P class="p6 ft4">VOL. '. ($params['check'] == 0 ? $params['amount'] : ceil($params['amount']/2)) .'</P></TD>

				</table>';
			}
		

		$body .= '</page></BODY>
		</HTML>';

		$p = $response->setResponse($body);
		return $p;
	}

	private function validation(array $params)
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
