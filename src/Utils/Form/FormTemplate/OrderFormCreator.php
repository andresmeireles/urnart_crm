<?php 

namespace App\Utils\Form\FormTemplate;

use App\Utils\Form\ResponseForm;
use App\Utils\Form\Parameters;
use App\Utils\Form\Interfaces\ResponseFormInterface;
use App\Utils\Form\Interfaces\CreateFormInterface;
use App\Utils\Validation\ValidatorJson;
use Respect\Validation\Validator as v;

class OrderFormCreator implements CreateFormInterface
{
	/**
	 * Create Form and save on database
	 * @param  array  $params parameters for Form
	 * @return ResponseFormInterface
	 */
	public function createForm(Parameters $parameters): ResponseFormInterface
	{
		ValidatorJson::validate($parameters->getNonClonedParameters(), [
			'clientName' => v::notEmpty(),
			'clientCity' => v::notEmpty()->not(v::numeric()),
			'formPg' => v::notEmpty()->not(v::numeric()),
			'freight' => v::optional(v::numeric()->positive()),
			'discount' => v::optional(v::numeric()->positive()),
			'transporter' => v::optional(v::alpha()->numeric()),
			'port' => v::optional(v::alpha()->numeric()),
			'observation' => v::optional(v::notBlank())
		]);

		foreach ($parameters->getClonedParameters() as $param) {
			ValidatorJson::validate($param, [
				'model' => v::notEmpty(),
				'price' => v::notEmpty()->positive()->not(v::alpha()),
				'amount' => v::notEmpty()->positive()->not(v::alpha())
			]);	
		}

		if (ValidatorJson::getErrors()) {
			$this->getMessage();
		}

		$response = new ResponseForm();

		$FormBody = $this->createBodyForm($parameters);

		return $response->setResponse($FormBody);
	}

	public function getMessage(): array
	{
		return ValidatorJson::getErrors();
	}

	private function createBodyForm(Parameters $parameters): string
	{
		$information = $parameters->getNonClonedParameters();
		$clonedParameters = $parameters->getClonedParameters();
		$totalPrice = 0;
		$totalAmount = 0;

		$information['freight'] = $information['freight'] != null ? $information['freight'] : 0;
		$information['discount'] = $information['discount'] != null ? $information['discount'] : 0; 

		$Form = '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
		<HTML>
		<HEAD>
		<META http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<META http-equiv="X-UA-Compatible" content="IE=8">
		<TITLE>Criação de Pedidos</TITLE>
		<META name="generator" content="BCL easyConverter SDK 5.0.08">

		<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", function () {
			window.print();
			});
			</script>
			<STYLE type="text/css">

			body {margin-top: 0px;margin-left: 0px;}

		#page_1 {position:relative; overflow: hidden;margin: 72px 0px 77px 24px;padding: 0px;border: none;width: 1099px;}
		#page_1 #id_1 {float:left;border:none;margin: 1px 0px 0px 0px;padding: 0px;border:none;width: 546px;overflow: hidden;}
		#page_1 #id_2 {float:left;border:none;margin: 2px 0px 0px 1px;padding: 0px;border:none;width: 552px;overflow: hidden;}

		#page_1 #p1dimg1 {position:absolute;top:0px;left:0px;z-index:-1;width:1066px;height:623px;}
		#page_1 #p1dimg1 #p1img1 {width:1066px;height:623px;}

			.dclr {clear:both;float:none;height:1px;margin:0px;padding:0px;overflow:hidden;}

			.ft0{font: 20px "Calibri";line-height: 21px;}
			.ft1{font: 18px "Calibri";line-height: 22px;}
			.ft2{font: 16px "Calibri";line-height: 19px;}
			.ft3{font: 1px "Calibri";line-height: 1px;}
			.ft4{font: 1px "Calibri";line-height: 12px;}
			.ft5{font: 17px "Calibri";line-height: 21px;}
			.ft6{font: 18px "Calibri";line-height: 21px;}
			.ft7{font: 1px "Calibri";line-height: 21px;}
			.ft8{font: 1px "Calibri";line-height: 3px;}
			.ft9{font: bold 18px "Calibri";line-height: 21px;}
			.ft10{font: bold 18px "Calibri";line-height: 22px;}
			.ft11{font: 1px "Calibri";line-height: 2px;}
			.ft12{font: bold 17px "Calibri";line-height: 21px;}

			.p0{text-align: left;padding-left: 139px;margin-top: 0px;margin-bottom: 0px;}
			.p1{text-align: left;padding-left: 4px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
			.p2{text-align: left;padding-left: 2px;margin-top: 0px;margin-bottom: 0px;}
			.p3{text-align: left;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
			.p4{text-align: left;padding-left: 3px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
			.p5{text-align: center;padding-left: 17px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
			.p6{text-align: center;padding-left: 2px;margin-top: 0px;margin-bottom: 0px;white-space: normal;}
			.p7{text-align: right;padding-right: 32px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
			.p8{text-align: right;padding-right: 42px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
			.p9{text-align: right;padding-right: 42px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
			.p10{text-align: right;padding-right: 90px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
			.p11{text-align: left;padding-left: 127px;margin-top: 0px;margin-bottom: 0px;}
			.p12{text-align: right;padding-right: 71px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
			.p13{text-align: center;padding-left: 129px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
			.p14{text-align: center;padding-left: 3px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}
			.p15{text-align: center;padding-right: 2px;margin-top: 0px;margin-bottom: 0px;white-space: nowrap;}

			.td0{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 74px;vertical-align: center;}
			.td1{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 206px;vertical-align: bottom;}
			.td2{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 16px;vertical-align: center;}
			.td3{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 92px;vertical-align: center;}
			.td4{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 127px;vertical-align: bottom;}
			.td5{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 74px;vertical-align: center;}
			.td6{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 206px;vertical-align: center;}
			.td7{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 16px;vertical-align: center;}
			.td8{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 92px;vertical-align: bottom;}
			.td9{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 127px;vertical-align: center;}
			.td10{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 17px;vertical-align: center;}
			.td11{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 93px;vertical-align: center;}
			.td12{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 74px;vertical-align: center;}
			.td13{padding: 0px;margin: 0px;width: 206px;vertical-align: bottom;}
			.td14{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 16px;vertical-align: center;}
			.td15{border-right: #000000 1px solid;padding: 0px;margin: 0px;width: 92px;vertical-align: center;}
			.td16{padding: 0px;margin: 0px;width: 127px;vertical-align: bottom;}
			.td17{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 110px;vertical-align: center;}
			.td18{border-left: #000000 1px solid;padding: 0px;margin: 0px;width: 74px;vertical-align: center;}
			.td19{padding: 0px;margin: 0px;width: 17px;vertical-align: bottom;}
			.td20{padding: 0px;margin: 0px;width: 93px;vertical-align: bottom;}
			.td21{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 222px;vertical-align: bottom;}
			.td22{border-right: #000000 1px solid;border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 90px;vertical-align: bottom;}
			.td23{border-top: #000000 1px solid;padding: 0px;margin: 0px;width: 130px;vertical-align: bottom;}
			.td24{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 222px;vertical-align: bottom;}
			.td25{border-right: #000000 1px solid;border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 90px;vertical-align: bottom;}
			.td26{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 130px;vertical-align: bottom;}
			.td27{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 223px;vertical-align: bottom;}
			.td28{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 91px;vertical-align: bottom;}
			.td29{border-bottom: #000000 1px solid;padding: 0px;margin: 0px;width: 314px;vertical-align: bottom;}
			.td30{padding: 0px;margin: 0px;width: 314px;vertical-align: center;}
			.td31{padding: 0px;margin: 0px;width: 130px;vertical-align: bottom;}

			.tr0{height: 31px;}
			.tr1{height: 12px;}
			.tr2{height: 23px;}
			.tr3{height: 21px;}
			.tr4{height: 22px;}
			.tr5{height: 3px;}
			.tr6{height: 2px;}
			.tr7{height: 66px;}
			.tr8{height: 47px;}

			.t0{width: 518px;margin-top: 4px;font: 18px "Calibri";}
			.t1{width: 519px;margin-top: 4px;font: 18px "Calibri";}

			</STYLE>
			</HEAD>

			<BODY>
			<input type="hidden" id="printer">
			<DIV id="page_1">

			<DIV class="dclr"></DIV>
			<DIV>
			<DIV id="id_1">
			<P class="p0 ft0">PEDIDO DE URNAS - VENDAS</P>
			<TABLE cellpadding=0 cellspacing=0 class="t0" border="1">
			<TR>
			<TD class="tr0 td0"><P class="p1 ft1"><b>CLIENTE</b></P></TD>
			<TD class="tr0 td1"><P class="p2 ft2">'. $information['clientName'] .'</P></TD>
			<TD class="tr0 td3"><P class="p4 ft1"><b>CIDADE</b></P></TD>
			<TD class="tr0 td4"><P class="p3 ft1">'. $information['clientCity'] .'</P></TD>
			</TR>
			<TR>
			<TD class="tr2 td5"><P class="p1 ft1"><b>DATA</b></P></TD>
			<TD class="tr2 td6"><P class="p5 ft5">11.06.2018</P></TD>
			<TD class="tr2 td11"><P class="p3 ft3">&nbsp;</P></TD>
			<TD class="tr2 td9"><P class="p3 ft3">&nbsp;</P></TD>
			</TR>
			<TR>
			<TD class="tr3 td5"><P class="p6 ft5"><b>QUANT</b></P></TD>
			<TD class="tr3 td6"><P class="p6 ft5"><b>PRODUTO</b></P></TD>
			<TD class="tr3 td8"><P class="p6 ft5"><b>PREÇO</b></P></TD>
			<TD class="tr3 td9"><P class="p6 ft5"><b>VALOR TOTAL</b></P></TD>
			</TR>';

			foreach ($clonedParameters as $cParameters) {
				$Form .= '<TR>
				<TD class="tr4 td5"><P class="p7 ft1">'. $cParameters['amount'] .'</P></TD>
				<TD class="tr4 td6"><P class="p5 ft1">'. $cParameters['model'] .'</P></TD>
				<TD class="tr4 td8"><P class="p6 ft5">R$ '. $cParameters['price'] .',00</P></TD>
				<TD class="tr4 td9"><P class="p6 ft5">R$ '. ($cParameters['price'] * $cParameters['amount']) .',00</P></TD>
				</TR>';

				$totalAmount += $cParameters['amount'];
				$totalPrice += ($cParameters['price'] * $cParameters['amount']);
			}

			$Form .= '<TR>
			<TD class="tr4 td5"><P class="p7 ft1">&nbsp;</P></TD>
			<TD class="tr4 td6"><P class="p5 ft1">&nbsp;</P></TD>
			<TD class="tr4 td8"><P class="p6 ft5">&nbsp;</P></TD>
			<TD class="tr4 td9"><P class="p6 ft5">&nbsp;</P></TD>
			</TR>
			<TR>
			<TD class="tr3 td12"><P class="p7 ft6"><b>'. $totalAmount .'</b></P></TD>
			<TD class="tr5 td6"><P class="p3 ft8">&nbsp;</P></TD>
			<TD class="tr5 td7"><P class="p3 ft8">&nbsp;</P	></TD>
			<TD class="tr3 td17"><P class="p6 ft5">R$ '. $totalPrice .',00</P></TD>
			</TR>
			<TR>
			<TD class="tr3 td5"><P class="p1 ft9">OBS</P></TD>
			<TD colspan=3 class="tr3 td17"><P class="p6 ft5">'. $information['observation'] .'</P></TD>
			</TR>
			<TR>
			<TD class="tr4 td5"><P class="p1 ft10">Form Pg</P></TD>
			<TD class="tr4 td6"><P class="p5 ft10">'. $information['formPg'] .'</P></TD>
			<TD class="tr4 td8"><P class="p4 ft10">Frete</P></TD>
			<TD class="tr4 td9"><P class="p9 ft10">R$ '. $information['freight'] .',00</P></TD>
			</TR>
			<TR>
			<TD class="tr4 td5"><P class="p1 ft10">Total</P></TD>
			<TD class="tr4 td6"><P class="p5 ft10">R$ '. (($totalPrice + $information['freight']) - $information['discount']) .',00</P></TD>
			<TD class="tr4 td8"><P class="p4 ft10">Desconto</P></TD>
			<TD class="tr4 td9"><P class="p9 ft10">R$ '. $information['discount'] .',00</P></TD>
			</TR>
			<TR>
			<TD class="tr4 td5"><P class="p1 ft10">Transp.</P></TD>
			<TD colspan=3 class="tr4 td17"><P class="p6 ft5">'. $information['transporter'] .'</P></TD>
			</TR>
			<TR>
			<TD class="tr4 td5"><P class="p1 ft10">Porto</P></TD>
			<TD colspan=3 class="tr4 td17"><P class="p6 ft5">'. $information['port'] .'</P></TD>
			</TR>
			</TABLE>
			</DIV>
			<DIV id="id_2">
			<P class="p11 ft0">PEDIDO DE URNAS - PRODUÇÃO</P>
			<TABLE cellpadding=0 cellspacing=0 class="t1" border="1">
			<TR>
			<TD class="tr0 td0"><P class="p1 ft1"><b>CLIENTE</b></P></TD>
			<TD class="tr0 td21"><P class="p2 ft2">'. $information['clientName'] .'</P></TD>
			<TD class="tr0 td22"><P class="p4 ft1"><b>CIDADE</b></P></TD>
			<TD class="tr0 td23"><P class="p4 ft1">'. $information['clientCity'] .'</P></TD>
			</TR>
			<TR>
			<TD class="tr2 td5"><P class="p1 ft1"><b>DATA</b></P></TD>
			<TD class="tr2 td27"><P class="p12 ft1">11.06.2018</P></TD>
			<TD class="tr2 td28"><P class="p3 ft3">&nbsp;</P></TD>
			<TD class="tr2 td26"><P class="p3 ft3">&nbsp;</P></TD>
			</TR>
			<TR>
			<TD class="tr3 td5"><P class="p6 ft5">QUANT</P></TD>
			<TD colspan=3 class="tr3 td29"><P class="p6 ft5">PRODUTO</P></TD>
			</TR>';

			foreach ($clonedParameters as $cParameters) {
				$Form .= '<TR>
				<TD class="tr4 td5"><P class="p14 ft1">'. $cParameters['amount'] .'</P></TD>
				<TD colspan=3 class="tr4 td29"><P class="p14 ft1">'. $cParameters['model'] .'</P></TD>
				</TR>';
			}

			$Form .='<TR>
			<TD class="tr4 td5"><P class="p7 ft1">&nbsp;</P></TD>
			<TD colspan=3 class="tr4 td6"><P class="p5 ft1">&nbsp;</P></TD>
			</TR>
			<TR>
			<TD class="tr2 td5"><P class="p14 ft1">'. $totalAmount .'</P></TD>
			<TD colspan=3 class="tr2 td27"><P class="p3 ft3">&nbsp;</P></TD>
			</TR>
			<TR>
			<TD valign="middle" class="tr7 td12"><P valign="middle" class="p15 ft12">OBS</P></TD>
			<TD colspan=3 class="tr7 td30"><P valign="middle" class="p6 ft10">'. $information['observation'] .'</P></TD>
			</TR>
			</TABLE>
			</DIV>
			</DIV>
			</DIV>
			</BODY>
			</HTML>';

			return $Form;
		}
	}