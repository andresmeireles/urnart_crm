<?php

namespace App\Utils\Reports;

use App\Utils\Reports\Interfaces\ReportInterface;
use Mpdf\Mpdf;

class TagReport implements ReportInterface
{
	private $report;

	public function create(array $params)
	{
		$pdf = new Mpdf();

		$x = '<img src="\logo-t.png">';

		$header = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
		<html>
		<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=8">
		<title>Etiqueta</title>
		<meta name="generator" content="BCL easyConverter SDK 5.0.08">
		<style type="text/css">

		body {margin-top: 0px;margin-left: 0px;}

		#page_1 {position:relative; overflow: hidden;margin: 0px 0px 0px 28px;padding: 0px;border: none;width: 766px;}

		#page_1 #p1dimg1 {position:absolute;top:4px;left:0px;z-index:-1;width:731px;height:1096px;}
		#page_1 #p1dimg1 #p1img1 {width:731px;height:1096px;}

		.ft0{font: bold 47px "Arial";line-height: 55px;}
		.ft1{font: 53px "Arial";line-height: 60px;}
		.ft3{font: 23px "Arial";line-height: 26px;}
		.ft4{font: 1px "Arial";line-height: 27px;}

		.p0{text-align: right;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
		.p1{text-align: center;padding-left: 366px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
		.p2{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}

		.td0{padding: 0px;margin: 0px;width: 240px;vertical-align: bottom;}
		.td1{padding: 0px;margin: 0px;width: 491px;vertical-align: bottom;}
		.td2{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 240px;vertical-align: bottom;}
		.td3{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 491px;vertical-align: bottom;}

		.tr2{height: 84px;}
		.tr3{height: 27px;}

		.t0{width: 731px;font: bold 47px "Arial";}

		</style>
		</head>
		<body>';

		foreach ($params as $param) {
			for ($c = 0; $c < $param['amount']; $c++) {
				$header .= '<div id="page_1">

				<table cellpadding=0 cellspacing=0 class="t0">

				<tr>
				<td class="tr2 td0"><p class="p0 ft0"><img src="\logo-t.png" width="60%" padding="200px"> '. $param['name'] .'</p></TD>
				<td class="tr2 td1"><p class="p1 ft1">'. ( $param['check'] == 0 ? $c+1 : ceil($c+1 / 2) ) .'</p></td>
				</tr>
				<tr>
				<td class="tr3 td2"><p class="p2 ft4">&nbsp;</p></td>
				<td class="tr3 td3"><p class="p1 ft3">VOL. '. ($param['check'] == 0 ? $param['amount'] : ceil($param['amount']/2)) .'</p></td>
				</tr>
				<!-- end row --> 

				</table></div>';
			}
		}

		$header .= '</BODY>
		</HTML>';

		$this->report = $header;

		echo $this->report;
		die();

		$pdf->write($this->report, 1);

		$pdf->output();
	}

	public function show()
	{

	}
}
