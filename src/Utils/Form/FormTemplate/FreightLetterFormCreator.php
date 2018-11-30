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
                'clientName' => v::notEmpty()->stringType()->not(v::numeric()),
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

        $body = '
		<style>
		.r-page { width: 100%; height: 95%;}
		.r-h-page { width: 100%; height: 50%; }
		.r-head { margin: 5px 15px 0px 20px; font-size: 1.2em; font-weight: bold; height: 15%; }
		.r-body { margin: 10px 30px 0px 40px; height: 45% }
		.rfoot { margin: 20px 35px 0px 35px; font-size: 1.1em; font-weight: bold; height: 30% }
		.r-0 { text-align: center }
		.r-t { text-align: center; font-size: 1.9em; font-weight: bold }
		.r-2, .r-3, .r-4 { font-size: 1.4em; }
		.r-body div { margin-bottom: 5px } 
		.rf-left { float: left }
		.rf-right { float: right }
		.rf-clear { clear: both }
		.r-b-25 { width: 25% }
		.r-b-50 { width: 50% }
		.r-b-75 { width: 75% }
		.r-b-100 { width: 100% }
		.r-t-center { text-align: center; }
		.rf-2 { margin: 70px 0px 0px 0px }
		.b-sup { border-top: 1px solid black }
		.break-p { page-break-after: always }
		</style>';

        foreach ($parameters as $param) {
            $freightFloat = number_format($param['freight'], 2, '.', ',');
            $number = $extenseNumber->converter($freightFloat);
            $freightPrice = number_format($param['freight'], 2, ',', '.');
            $body .= '<div class="r-page break-p">';

            $hBody = '<div class="r-h-page">
			<div class="rform">

			<div class="r-head">
			<span class="rf-right">Urnas Mart Ltda</span>
			<span class="rf-left r-b-75">05.020.839/0001-05</span>
			<span class="rf-left r-b-75">(91) 93256-1106</span>
			<div class="rf-clear"></div>
			</div>

			<div class="r-body">
			<div class="r-0">
			<span class="r-t">Carta Frete</span>
			</div>
			<div class="r-1">À</div>
			<div class="r-2">
			<span class="rf-right">R$ '. $freightPrice .'</span>
			<span class="rf-left r-b-75">'. $param['number'] .' - '. $param['clientName'] .'</span>
			<span class="rf-left r-b-75">'. $param['clientCity'] .' - '. $param['clientState'] .'</span>
			<div class="rf-clear"></div>
			</div>
			<div class="r-3">
			<span>
			Pagar ao portador o Valor de R$ '. $freightPrice .' ('. strtoupper($number) .') referente ao frete de urnas do pedido de
			número '. $param['orderNumber'] .', conforme combinado.
			</span>
			</div>
			<div class="r-4">Atenciosamente</div>
			</div>

			<div class="rfoot">
			<div class="rf-1">
			<div class="r-t-center ">
			<span class="">DATA: ______/______/___________</span>  
			</div>
			<div class="rf-clear"></div>      
			</div>
			<div class="rf-2">
			<span class="rf-left r-b-25 b-sup">Recebedor</span>
			<span class="rf-right r-b-25 b-sup">Cliente</span>     
			</div>
			</div>

			</div>  
			</div>';

            $body .= $hBody.''.$hBody;

            $body .= '</div>';
        }

        return $body;
    }
}
