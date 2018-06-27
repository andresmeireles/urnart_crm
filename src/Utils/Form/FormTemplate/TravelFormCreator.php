<?php 

namespace App\Utils\Form\FormTemplate;

use \App\Utils\Form\ResponseForm;
use App\Utils\Form\Parameters;
use \App\Utils\Form\Interfaces\ResponseFormInterface;
use \App\Utils\Form\Interfaces\CreateFormInterface;
use \App\Utils\Validation\ValidatorJson;
use \Respect\Validation\Validator as v;

class TravelFormCreator implements CreateFormInterface
{
	private $message;

	public function createForm(Parameters $parameters): ResponseFormInterface
	{
		foreach ($parameters->getClonedParameters() as $clonedParameter) {
			ValidatorJson::validate($clonedParameter, [
				'name' => v::notEmpty()->not(v::numeric()),
				'city' => v::notEmpty()->not(v::numeric())
			]);	
		}

		ValidatorJson::validate($parameters->getNonClonedParameters(), [
			'driverName' => v::notEmpty()->not(v::numeric())
		]);

		if (ValidatorJson::getErrors()) {
			$this->getMessage();
		}

		$body = $this->createFormBody($parameters);

		echo $body;
		die();
	}

	public function getMessage()
	{
		return $this->message = ValidatorJson::getErrors();
	}

	private function createFormBody(Parameters $parameters): string
	{
		$parameter = $parameters->getNonClonedParameters();
		$clonedParameters = $parameters->getClonedParameters();
		$background = file_get_contents(__DIR__.'/travel-form/file');
		$itemsCounter = 1;
		$pageCounter = 1;

		$body = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
		<HTML>
		<HEAD>
		<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<META http-equiv="X-UA-Compatible" content="IE=8">
		<TITLE>bcl_1500003992.htm</TITLE>
		<META name="generator" content="BCL easyConverter SDK 5.0.08">
		<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", function () {
			window.print();
			});
			</script>
		<STYLE type="text/css">

		body {margin-top: 0px;margin-left: 0px;}

		#page_1 {position:relative; overflow: hidden;margin: 14px 0px 15px 0px;padding: 0px;border: none;width: 794px;}

		#page_1 #p1dimg1 {position:absolute;top:0px;left:19px;z-index:-1;width:679px;height:798px;}
		#page_1 #p1dimg1 #p1img1 {width:679px;height:798px;}

		#page_2 {position:relative; overflow: hidden;margin: 14px 0px 55px 0px;padding: 0px;border: none;width: 794px;}

		#page_2 #p2dimg1 {position:absolute;top:0px;left:19px;z-index:-1;width:679px;height:798px;}
		#page_2 #p2dimg1 #p2img1 {width:679px;height:798px;}

		.dclr {clear:both;float:none;height:1px;margin:0px;padding:0px;overflow:hidden;}

		.ft0{font: bold 27px "Arial";line-height: 32px;}
		.ft1{font: bold 12px "Arial";line-height: 15px;}
		.ft2{font: 19px "Arial";line-height: 22px;}
		.ft3{font: bold 19px "Arial";line-height: 22px;}
		.ft4{font: 16px "Arial";line-height: 18px;}
		.ft5{font: bold 16px "Arial";line-height: 19px;}
		.ft6{font: 13px "Arial";line-height: 16px;}
		.ft7{font: italic 13px "Arial";line-height: 16px;}

		.p0{text-align: left;padding-left: 277px;margin-top: 6px;margin-bottom: 0px;}
		.p1{text-align: left;padding-left: 278px;margin-top: 11px;margin-bottom: 0px;}
		.p2{text-align: left;padding-left: 252px;margin-top: 45px;margin-bottom: 0px;}
		.p3{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
		.p4{text-align: left;padding-left: 48px;margin-top: 13px;margin-bottom: 0px;}
		.p5{text-align: left;padding-left: 433px;margin-top: 12px;margin-bottom: 0px;}
		.p6{text-align: left;padding-left: 347px;margin-top: 13px;margin-bottom: 0px;}
		.p7{text-align: left;padding-left: 48px;margin-top: 12px;margin-bottom: 0px;}
		.p8{text-align: left;padding-left: 52px;margin-top: 11px;margin-bottom: 0px;}
		.p9{text-align: left;padding-left: 48px;margin-top: 11px;margin-bottom: 0px;}
		.p10{text-align: left;padding-left: 347px;margin-top: 12px;margin-bottom: 0px;}
		.p11{text-align: left;padding-left: 291px;margin-top: 13px;margin-bottom: 0px;}
		.p12{text-align: left;padding-left: 373px;margin-top: 13px;margin-bottom: 0px;}
		.p13{text-align: right;padding-right: 48px;margin-top: 62px;margin-bottom: 0px;}
		.p14{text-align: left;padding-left: 113px;margin-top: 11px;margin-bottom: 0px;}
		.p15{text-align: left;padding-left: 291px;margin-top: 11px;margin-bottom: 0px;}
		.p16{text-align: left;padding-left: 347px;margin-top: 41px;margin-bottom: 0px;}
		.p17{text-align: left;padding-left: 52px;margin-top: 12px;margin-bottom: 0px;}
		.p18{text-align: left;padding-left: 291px;margin-top: 12px;margin-bottom: 0px;}
		.p19{text-align: left;padding-left: 373px;margin-top: 12px;margin-bottom: 0px;}
		.p20{text-align: right;padding-right: 48px;margin-top: 225px;margin-bottom: 0px;}

		.td0{padding: 0px;margin: 0px;width: 245px;vertical-align: bottom;}
		.td1{padding: 0px;margin: 0px;width: 180px;vertical-align: bottom;}
		.td2{padding: 0px;margin: 0px;width: 28px;vertical-align: bottom;}

		.tr0{height: 18px;}

		.t0{width: 453px;margin-left: 48px;margin-top: 45px;font: 16px "Arial";}

		</STYLE>
		</HEAD>

		<BODY>
		<DIV id="page_1">
		<DIV id="p1dimg1">
		<IMG src="'. $background .'" alt=""></DIV>

		<DIV class="dclr"></DIV>
		<P class="p0 ft0">Urnas Mart Ltda</P>
		<P class="p1 ft1">CNPJ. <NOBR>05.020.839/0001-05</NOBR></P>
		<P class="p2 ft3">Relatório de Viagem <SPAN class="ft2">– </SPAN>'. $parameter['driverName'] .'</P>
		<TABLE cellpadding=0 cellspacing=0 class="t0">
		<TR>
		<TD class="tr0 td0"><P class="p3 ft4">Data Saída:</P></TD>
		<TD class="tr0 td1"><P class="p3 ft4">Hora:</P></TD>
		<TD class="tr0 td2"><P class="p3 ft4">KM:</P></TD>
		</TR></TABLE>
		<P class="p4 ft4">Chegada:<SPAN style="padding-left:178px;">Hora:</SPAN><SPAN style="padding-left:139px;">KM:</SPAN></P>
		<P class="p5 ft4">PERCOR:</P>';

		foreach ($clonedParameters as $parameter) {
			if ($itemsCounter == 0) {
				$body .= '<DIV id="page_2">
				<DIV id="p2dimg1">
				<IMG src="'. $background .'" alt=""></DIV>

				<DIV class="dclr"></DIV>
				<P class="p0 ft0">Urnas Mart Ltda</P>
				<P class="p1 ft1">CNPJ. <NOBR>05.020.839/0001-05</NOBR></P>
				<P class="p16 ft5">'. $parameter['city'] .'</P>
				<P class="p7 ft6">'. $parameter['name'] .'</P>
				<P class="p8 ft6">Dia____/___/____ Hora Chegada: ___:___ DIA: ____/____/____ Hora Saída: ___:___</P>
				<P class="p9 ft4">_____________________________________________________________________________</P>
				<P class="p4 ft4">_____________________________________________________________________________</P>
				<P class="p7 ft4">_____________________________________________________________________________</P>';

				$itemsCounter++;
				continue;
			}

			if ($itemsCounter == 4) {
				$body .= '<P class="p12 ft5">'. $parameter['city'] .'</P>
				<P class="p9 ft6">'. $parameter['name'] .'</P>
				<P class="p8 ft6">Dia____/___/____ Hora Chegada: ___:___ DIA: ____/____/____ Hora Saída: ___:___</P>
				<P class="p9 ft4">_____________________________________________________________________________</P>
				<P class="p4 ft4">_____________________________________________________________________________</P>
				<P class="p4 ft4">_____________________________________________________________________________</P>
				<P class="p13 ft6">'. $pageCounter .'</P>
				<P class="p14 ft6">Rua Pedro Mesquita, 1260 Centro – <NOBR>67200-00</NOBR> – <NOBR>Marituba-PA</NOBR> – Fone: (91) 3256 1106 – (91)</P>
				<P class="p15 ft7"><NOBR><SPAN class="ft6">E-MAIL:</SPAN></NOBR><SPAN class="ft6"> </SPAN>vendas@urnart.com.br</P>
				</DIV>';
				$pageCounter++;
				$itemsCounter = 0;
				continue;
			}

			$body .= '<P class="p6 ft5">'. $parameter['city'] .'</P>
			<P class="p7 ft6">'. $parameter['name'] .'</P>
			<P class="p8 ft6">Dia____/___/____ Hora Chegada: ___:___ DIA: ____/____/____ Hora Saída: ___:___</P>
			<P class="p9 ft4">_____________________________________________________________________________</P>
			<P class="p7 ft4">_____________________________________________________________________________</P>
			<P class="p4 ft4">_____________________________________________________________________________</P>';
			$itemsCounter++;
		}

		return $body;
	}
}