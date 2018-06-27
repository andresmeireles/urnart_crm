<?php 

namespace App\Utils\Form\FormTemplate;

use App\Utils\Form\Interfaces\ResponseFormInterface;
use App\Utils\Form\Interfaces\CreateFormInterface;
use App\Utils\Form\Parameters;
use App\Utils\Form\ResponseForm;
use App\Utils\Validation\ValidatorJson;
use Respect\Validation\Validator as v;
use WGenial\NumeroPorExtenso\NumeroPorExtenso;

/**
 * Create a Freight Letter form
 */
class FreightLetterFormCreator implements CreateFormInterface
{
	public function createForm(Parameters $parameters): ResponseFormInterface
	{
		$response = new ResponseForm();

		$clonedParameters = $parameters->getClonedParameters();

		foreach ($clonedParameters as $parameter) {
			ValidatorJson::validate($parameter, [
				'number' => v::notEmpty()->numeric()->positive(),
				'clientName' => v::notEmpty()->alpha()->not(v::numeric()),
				'clientCity' => v::notEmpty()->alpha()->not(v::numeric()),
				'clientState' => v::notEmpty()->alpha()->length(2, 2)->not(v::numeric()),
				'freight' => v::notEmpty()->numeric()->positive(),
				'orderNumber' => v::notEmpty()->numeric()->positive(),
			]);
		}

		if (ValidatorJson::getErrors()) {
			return $response->setErrorMessage($this->getMessage());
		}

		$body = $this->createBody($clonedParameters);

		return $response->setResponse($body);
	}

	public function getMessage()
	{
		return ValidatorJson::getErrors();
	}

	private function createBody(array $parameters)
	{
		$imageBackground = file_get_contents(__DIR__.'/carta-frete/background');
		$imageSign = file_get_contents(__DIR__.'/carta-frete/sign');
		$extenseNumber = new NumeroPorExtenso();

		$body = '<script type="text/javascript">
		document.addEventListener("DOMContentLoaded", function () {
			window.print();
			});
			</script>
		<style>
  		#page { size: A4 portrait;}
  		#half-page { height: 14cm; width: 21cm; margin: 0px 0px 100px 0px; background: url('. $imageBackground .') center no-repeat; -webkit-print-color-adjust: exact; }
  		#form { padding: 10px; font: 15px "Arial"; }
		.f-right { float: right; }
		.f-left { float: left; }
		.clear { clear:both }
		.body { padding: 0px 30px 0px 30px; }
		.body p, div { font: 18px "Arial"}  

		.footer { padding: 0px 30px 0px 30px; font: 18px "Arial"; }
		.img img { width: 120px; height: 100px; display:inline;}
		.date { margin: 70px 250px 0px 0px; float:right}
		.center { text-align:center }
		.sign { margin: 30px 0px 0px 0px }
		.c { margin: 0px 0px 0px 90px;}
		</style>

		<body>';

		foreach ($parameters as $param) {
			$freightFloat = number_format($param['freight'], 2, '.', ',');
			$number = $extenseNumber->converter($freightFloat);
			$freightPrice = number_format($param['freight'], 2, ',', '.');
			$body .= '<div id="page">';

			$halfPage = '<div id="half-page">
			<div id="form">
			<div class="head">
			<div class="f-right">Urnas Mart Ltda</div>
			<div class="f-left">05.020.839/0001.05</div>
			<div class="clear"></div>
			<div class="f-left">(91) 3256 1106</div>
			</div>
			<div class="clear"></div>
			<div class="body">
			<h2 class="center">CARTA FRETE</h2>
			<p>Á</p>
			<div><span class="f-left">'. $param['number'] .' - '. $param['clientName'] .'</span><span class="f-right">R$ '. $freightPrice .'</span><br><span class="f-left">'. $param['clientCity'] .' - '. $param['clientState'] .'</span></div>
			<div class="clear"></div>
			<p class>Pagar ao portador o Valor de R$ '. $freightPrice .' ('. strtoupper($number) .') referente ao frete de urnas do pedido de número '. $param['orderNumber'] .', conforme combinado.  
			</p><p>Atenciosamente,</p>
			</div>
			<div class="footer">
			<div class="img f-left">
			<img src="'. $imageSign .'">
			<div class="clear"></div>
			<small>Urnas Mart</small>
			</div>
			<div class="date">Data:____/____/________</div>
			<div class="clear"></div>
			<div class="sign">
			<div class="f-left">
			<span>____________________________</span>
			<br>
			<div class="c">Cliente</div>
			</div>
			<div class="f-right">
			<span>____________________________</span>
			<br>
			<div class="c">Recebedor</div>
			</div>
			</div>
			<div class="clear"></div>
			</div>
			</div>
			</div>';

			$body .= $halfPage .''. $halfPage;

			$body.= '</div>';
		}

		$body .= '</body>';

		return $body;
	}
}