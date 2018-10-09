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
class ReceiptFormCreator implements CreateFormInterface
{
	public function createForm(Parameters $parameters): ResponseFormInterface
	{

		$clonedParameters = $parameters->getClonedParameters();
		
		foreach ($clonedParameters as $parameter) {
			ValidatorJson::validate($parameter, [
				'number' => v::notEmpty()->numeric()->positive(),
				'clientName' => v::notEmpty()->alpha()->not(v::numeric()),
				'clientCity' => v::notEmpty()->alpha()->not(v::numeric()),
				'clientState' => v::notEmpty()->alpha()->length(2, 2)->not(v::numeric()),
				'price' => v::notEmpty()->numeric()->positive(),
				'orderNumber' => v::notEmpty()->numeric()->positive(),
			]);
		}

		if (ValidatorJson::getErrors()) {
			return new ResponseForm(null, 'Portrait', $this->getMessage());
		}

		$body = $this->createBody($clonedParameters);

		return new ResponseForm($body);
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

		$body = '
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<script>
			document.addEventListener("DOMContentLoaded", function () {
				window.print();
				});
		</script>
		<style>
		#page { width: 100%; height: 100% }
  		#half-page { height: 50%; width: 100%; margin: 10px 0px 10px 0px; }
  		#form { padding: 10px; font: 15px "Arial"; }
		.f-right { float: right; }
		.f-left { float: left; }
		.clear { clear:both }
		.body { padding: 0px 30px 0px 30px; }
		.body p, .body div { font: 18px "Arial"}  

		.footer { padding: 0px 30px 0px 30px; font: 18px "Arial"; }
		.img img { width: 120px; height: 100px; display:inline;}
		.date { margin: 70px 250px 0px 0px; float:right}
		.center { text-align:center }
		.sign { margin: 30px 0px 0px 0px }
		.c { margin: 0px 0px 0px 90px;}
		</style>';

        foreach ($parameters as $param) {
            $freightFloat = number_format($param['price'], 2, '.', '');
            $number = $extenseNumber->converter($freightFloat);
            $freightPrice = number_format($param['price'], 2, ',', '.');
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
			<h2 class="center">RECIBO</h2>
			<p>Á</p>
			<div><span class="f-left">'. $param['number'] .' - '. $param['clientName'] .'</span><span class="f-right">R$ '. $freightPrice .'</span><br><span class="f-left">'. $param['clientCity'] .' - '. $param['clientState'] .'</span></div>
			<div class="clear"></div>
			<p class>Pagar ao portador o Valor de R$ '. $freightPrice .' ('. strtoupper($number) .') referente a quitação do pedido de número '. $param['orderNumber'] .', ao qual damos plena quitação.  
			</p><p>Atenciosamente,</p>
			</div>
			<div class="footer">
			<div class="img f-left">
			<img src="'. $imageSign .'">
			<div class="clear"></div>
			<small>Urnas Mart</small>
			</div>
			<div class="clear"></div>
			<div class="sign">
			<div class="f-left">
			<span>______/_______/_____________</span>
			<br>
			<div class="c">Data</div>
			</div>
			<div class="f-right">
			<span>____________________________</span>
			<br>
			<div class="c">Cliente</div>
			</div>
			</div>
			<div class="clear"></div>
			</div>
			</div>
			</div>';

            $body .= $halfPage .''. $halfPage;

            $body.= '</div>';
		}

		return $body;
	}
}