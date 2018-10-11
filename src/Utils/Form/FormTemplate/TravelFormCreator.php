<?php 

namespace App\Utils\Form\FormTemplate;

use App\Utils\Form\ResponseForm;
use App\Utils\Form\Parameters;
use App\Utils\Form\Interfaces\ResponseFormInterface;
use App\Utils\Form\Interfaces\CreateFormInterface;
use App\Utils\Validation\ValidatorJson;
use App\Utils\Validation\ValidationFields;
use Respect\Validation\Validator as v;

class TravelFormCreator implements CreateFormInterface
{
	public function createForm(Parameters $parameters): ResponseFormInterface
	{
		foreach ($parameters->getClonedParameters() as $value) {
			ValidatorJson::validate($value, [
				'name' => v::notEmpty(),//->alpha()->not(v::numeric()),
				'city' => v::notEmpty()
			]);		
		}

		ValidatorJson::validate($parameters->getNonClonedParameters(), [
			'driverName' => v::notEmpty()->not(v::numeric())
		]);

		$body = $this->createFormBody($parameters);

		return new ResponseForm($body);
	}

	private function createFormBody(Parameters $parameters): string
	{
		$parameter = $parameters->getNonClonedParameters();
		$clonedParameters = $parameters->getClonedParameters();
		$background = file_get_contents(__DIR__.'/travel-form/file');
		$logo = file_get_contents(__DIR__.'/etiqueta/logo');
		$itemsCounter = 1;
		$pageCounter = 1;

		$body = '
		<style>
		
		.first-page { margin: 0px 0px 0px 0px; width: 100%; height: 100%; position: relative; background: url("") center no-repeat; -webkit-print-color-adjust: exact;}
		.page { margin: 80px 0px 0px 0px; width: 100%; height: 100%; position: relative; background: url("") center no-repeat; -webkit-print-color-adjust: exact;}
		.content { padding: 15px; }

		.fit { width: 300px; height: 150px }
		.f-left { float: left; }
		.clear { clear: both;}  
		.center { text-align: center}

		.logo_img { width: 19%}
		.logo_img logo_img { margin: 0 0 0 0;}
		.head { margin: 20 0 0 100;}
		.head span { display: block; margin: 3px 0px 0 0px; padding: 0px 0px 0px 100px;}
		.rzs { font-weight: bolder; font-size: 25px }

		.first-body { margin: 50px 0 0px 0px; }
		.body { margin: 0px 0 0px 0px; }
		.title { font: bold 20px "Fire Sans", serif; margin: 0 0 20px 0; }
		.row-f .title { margin: 0px 0 20px 0px}
		.row-f { margin: 10px 0px 0px 0px; }
		.row-f div { margin: 0 0 6px 0;}
		.line span { margin: 0px 180px 0px 0px; }
		.tp { margin-left: 434px}

		.h-line { margin: 0px 0px 0px 0px; }
		.h-line hr { margin: 20px 0px 30px 0px; }
		.row-f .c-name { margin: 10px 0px 10px 0px; }
		.info span { margin: 0px 100px 0px 0px; }

		.footer { position: absolute; bottom: 0; left: 0; right: 0; margin: 0 0 10 0; }
		.f-content span { display: block; margin: 3px 0px 0px 0px; font: italic 15px "Fire Sans", serif ;}
		.pg_number { right: 0; position: absolute; margin: 0 20px 0 0px; }
		</style>

		<div id="doc">

		<div class="first-page">
		<div class="content">
		<div class="a-header">
		<div class="f-left logo_img"><logo_img class="fit" src="'. $logo .'"></div>
		<div class="f-left head"><span class="rzs">Urnas Mart Ltda</span><span class="cnpj">005.020.839/0001-05</span></div>
		<div class="clear"></div>
		</div>
		<div class="first-body">
		<div class="row-f">
		<div class="title center"><span>Relátorio de Viagem: '. $parameter['driverName'] .'</span></div>
		<div class="line center"><span>Data Saida: &nbsp;&nbsp;&nbsp;&nbsp;</span><span>Hora:</span><span>KM:</span></div>
		<div class="line center"><span>Data Chegada:</span><span>Hora:</span><span>KM:</span></div>
		<span class="tp">Total Percorrido:</span>        
		</div>';

		foreach ($clonedParameters as $param) {
			
			if ($itemsCounter == 0) {
				$body .= '<div class="footer">
				<span class="pg_number">'. $pageCounter .'</span>
				<div class="f-content center">
				<span class="address">Rua Pedro Mesquita, 1260, 67200-00. Maritua -Pará</span>
				<span class="email">Email: vendas@urnart.com.br</span>
				</div>
				</div>
				</div>
				</div>
				</div>
				
				<div class="page">
				<div class="content">
				<div class="a-header">
				<div class="f-left logo_img"><logo_img class="fit" src="'. $logo .'"></div>
				<div class="f-left head"><span class="rzs">Urnas Mart Ltda</span><span class="cnpj">005.020.839/0001-05</span></div>
				<div class="clear"></div>
				</div>
				<div class="body">
				<div class="row-f center">
				<div><span class="title">'. $param['city'] .'</span></div>
				<div class="c-name"><span>'. $param['name'] .'</span></div>
				<div class="info"><span>Data Chegada:</span><span>Hora:</span><span>Data Chegada:</span><span>Hora:</span></div>
				<div class="h-line"><hr><hr><hr></div>
				</div>';
				$pageCounter++;
				$itemsCounter++;
				continue;
			}

			if ($itemsCounter == 4) {
				$body .= '<div class="row-f center">
				<div><span class="title">'. $param['city'] .'</span></div>
				<div class="c-name"><span>'. $param['name'] .'</span></div>
				<div class="info"><span>Data Chegada:</span><span>Hora:</span><span>Data Chegada:</span><span>Hora:</span></div>
				<div class="h-line"><hr><hr><hr></div>
				</div>';
				$itemsCounter = 0;
				continue;
			}

			$body .= '<div class="row-f center">
			<div><span class="title">'. $param['city'] .'</span></div>
			<div class="c-name"><span>'. $param['name'] .'</span></div>
			<div class="info"><span>Data Chegada:</span><span>Hora:</span><span>Data Chegada:</span><span>Hora:</span></div>
			<div class="h-line"><hr><hr><hr></div>
			</div>';	

			$itemsCounter++;
		}

		$body .= '
		<div class="footer">
		<span class="pg_number">'. $pageCounter .'</span>
		<div class="f-content center">
		<span class="address">Rua Pedro Mesquita, 1260, 67200-00. Maritua -Pará</span>
		<span class="email">Email: vendas@urnart.com.br</span>
		</div>
		</div>
		</div>
		</div>
		</div>
		</div>';

		return $body;
	}
}