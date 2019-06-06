<?php

namespace App\Utils\Form\FormTemplate;

use App\Utils\Form\Interfaces\CreateFormInterface;
use App\Utils\Form\Interfaces\ResponseFormInterface;
use App\Utils\Form\Parameters;
use App\Utils\Form\ResponseForm;

class RemandFormCreator implements CreateFormInterface
{
    public function createForm(Parameters $parameters): ResponseFormInterface
    {
        $response = new ResponseForm();

        if ($parameters->getClonedParameters() == null) {
            $body = $this->chequeBody($parameters->getNonClonedParameters());

            return $response->setResponse($body);
        }

        $body = $this->productBody($parameters);

        return $response->setResponse($body);
    }

    public function getMessage()
    {
    }

    private function chequeBody(array $parameters): string
    {
        $imgSign = file_get_contents(__DIR__.'/carta-frete/sign');
        $imgBackground = file_get_contents(__DIR__.'/carta-frete/background');

        $body = '
			<style>
		#half-page { height: 50%; width: 100%; margin: 0px 0px 0px 0px; background: url('. $imgBackground .') center no-repeat; -webkit-print-color-adjust: exact; }
		#form { padding: 10px; font: 15px "Arial"; }
			.f-right { float: right; }
			.f-left { float: left; }
			.clear { clear:both }
			.body-cf { padding: 0px 30px 0px 30px; }
			.body-cf { font: 18px "Arial"}  

			.footer { padding: 0px 30px 0px 30px; font: 18px "Arial"; }
			.logo_img logo_img { width: 120px; height: 100px; display:inline;}
			.date { margin: 70px 250px 0px 0px; float:right}
			.center { text-align:center }
			.sign { margin: 30px 0px 0px 0px }
			.c { margin: 0px 0px 0px 90px;}

			</style>

			<body>
			<div id="page">
			<div id="half-page">
			<div id="form">
			<div class="head">
			<div class="f-right">Urnas Mart Ltda</div>
			<div class="f-left">05.020.839/0001.05</div>
			<div class="clear"></div>
			<div class="f-left">(91) 3256 1106</div>
			</div>
			<div class="clear"></div>
			<div class="body-cf">
			<h2 class="center">DEVOLUÇÃO DE CHEQUE</h2>
			<div>Á</div>
			<div><span class="f-left">'. $parameters['clientNumber'] .' - '. $parameters['clientName'] .'</span><br><span class="f-left">'. $parameters['clientCity'] .'</span></div>
			<div class="clear"></div><p></p>
			<div class>Nome: '. $parameters['chqName'] .'</div>
			<div class>N° do Cheque: '. $parameters['chqNumber'] .'</div>
			<div class>Vencimento: '. $parameters['chqDate'] .'</div>
			<div class>Valor: '. $parameters['chqValue'] .'</div>
			<div class>Banco: '. $parameters['chqBank'] .'</div>

			<p>Atenciosamente,</p>
			</div>
			<div class="footer">
			<div class="logo_img f-left">
			<logo_img src="'. $imgSign .'">
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
			</div>
			</div>
			</body>';

        return $body.''.$body;
    }

    private function productBody(Parameters $parameters): string
    {
        $imgSign = file_get_contents(__DIR__.'/carta-frete/sign');
        $imgBackground = file_get_contents(__DIR__.'/carta-frete/background');

        $param = $parameters->getNonClonedParameters();
        $cParam = $parameters->getClonedParameters();

        $body = '
				</script>
				<style>
			    #page { size: A4 portrait;}
			    #half-page { height: 13cm; width: 21cm; margin: 0px 0px 80px 0px; background: url("'. $imgBackground .'") center no-repeat; -webkit-print-color-adjust: exact; }
			    #form { padding: 10px; font: 15px "Arial"; }
				.f-right { float: right; }
				.f-left { float: left; }
				.clear { clear:both }
				.body-cf { padding: 0px 30px 0px 30px; }
				.body-cf { font: 18px "Arial"}  

				.footer { padding: 0px 30px 0px 30px; font: 18px "Arial"; }
				.logo_img logo_img { width: 120px; height: 100px; display:inline;}
				.date { margin: 70px 250px 0px 0px; float:right}
				.center { text-align:center }
				.sign { margin: 30px 0px 0px 0px }
				.c { margin: 0px 0px 0px 90px;}

				</style>

				<body>
				<div id="page">
				<div id="half-page">
				<div id="form">
				<div class="head">
				<div class="f-right">Urnas Mart Ltda</div>
				<div class="f-left">05.020.839/0001.05</div>
				<div class="clear"></div>
				<div class="f-left">(91) 3256 1106</div>
				</div>
				<div class="clear"></div>
				<div class="body-cf">
				<h2 class="center">PROTOCOLO DE DEVOLUÇÃO</h2>
				<p>Á</p>
				<div><span class="f-left">'. $param['clientNumber'] .' - '. $param['clientName'] .'</span><br><span class="f-left">'. $param['clientCity'] .'</span></div>
				<div class="clear"></div>
				<p class>Produtos para <b>DEVOLUÇÃO</b>:</p>';

        foreach ($cParam as $p) {
            $body .= '<div class="center">'. $p['remandQnt'] .' - '. $p['remandProduct'] .'</div>';
        }
                
        $body .= '<p>Atenciosamente,</p>
				</div>
				<div class="footer">
				<div class="logo_img f-left">
				<logo_img src="'. $imgSign .'">
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
				</div>
				</div>
				</body>';

        return $body.''.$body;
    }
}
