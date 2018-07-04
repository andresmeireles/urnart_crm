<?php

namespace App\Utils\Form\FormTemplate;

use \App\Utils\Form\ResponseForm;
use App\Utils\Form\Parameters;
use \App\Utils\Form\Interfaces\ResponseFormInterface;
use \App\Utils\Form\Interfaces\CreateFormInterface;
use \App\Utils\Validation\ValidatorJson;
use \Respect\Validation\Validator as v;

class TagFormCreator implements CreateFormInterface
{
	public function createForm(Parameters $params): ResponseFormInterface 
	{
		$parameters = $params->getClonedParameters();

		$response = new ResponseForm();

		$image = file_get_contents(__DIR__.'/etiqueta/logo');

		$pageCounter = 0;

		$body = '
			<style media="print">
				#page { width: 21cm; height:30.7cm; padding: 0px 10px 0px 10px; margin: 0px 0px 39.5px 0px; border: 3px solid green}
			img { max-width: 100%; max-height: 100%; }

			.top { margin-bottom: 30px; margin-top: 20px;}
			.fit { display: inline-block }	
			.tag {width: 100%; margin: 10 0 17 0;}
			.img { width: 23%;}
			.clear { clear: both; }
			.right { float: right }
			.left { float: left }
			.font-sz0 { font-size: 40px }
			.font-sz1 { font-size: 35px }
			.font-sz2 { font-size: 22px }
			.c-0 { width: 12%; height: 4%}
			.c-1 { width: 58%; height: 4%; margin-left: 10px}
			.c-2 { width: 20%; height: 4%}
			.cb-0 { width: 12%; height: 2%}
			.cb-1 { width: 58%; height: 2%; margin-left: 10px}
			.cb-2 { width: 20%; height: 2%}
			.center { text-align: center }
			.bottom .clear { border-bottom: 1px solid black; padding-top: 6px;}
			</style>

			<style>

		img { max-width: 100%; max-height: 100%; }

		.top { margin-bottom: 10px;}
		.fit { display: inline-block }	
		.tag {width: 100%; margin: 10 0 17 0;}
		.img { width: 23%;}
		.clear { clear: both; }
		.right { float: right }
		.left { float: left }
		.font-sz0 { font-size: 40px }
		.font-sz1 { font-size: 35px }
		.font-sz2 { font-size: 22px }
		.c-0 { width: 15%; height: 4%}
		.c-1 { width: 58%; height: 4%; margin-left: 10px;}
		.c-2 { width: 20%; height: 5%}
		.cb-0 { width: 15%; height: 2%}
		.cb-1 { width: 58%; height: 2%; margin-left: 10px}
		.cb-2 { width: 20%; height: 2%}
		.center { text-align: center }
		.bottom .clear { border-bottom: 1px solid black; padding-top: 6px;}
		</style>

			<div id="page">';

			foreach ($parameters as $param) {

				for ($c = 0; $c < $param['amount']; $c++) {
					$count = $c;
					$count++;
					
					if ($pageCounter == 11) {
						$body .= '</div><div id="page">';
						$pageCounter = 0;
					} 

					$body .= '<div class="tag">
					<div class="top">
					<div class="img left c-0">
					<img src="'. $image .'">
					</div>
					<div class="left c-1"><span class="font-sz1">'. $param['name'] .'</span></div>
					<div class="right c-2 center"><span class="font-sz0">'. ( isset($param['check']) ? ceil($count/2) : $count ) .'</span></div>
					</div>
					<div class="clear"></div>
					<div class="bottom">
					<div class="left cb-0">&nbsp;</div>
					<div class="left cb-1"><span class="font-sz2">'. $param['city'] .'</span></div>
					<div class="right center cb-2"><span class="font-sz2">VOL. '. ( isset($param['check']) ? ceil($param['amount']/2) : $param['amount']) .'</span></div>    
					<div class="clear"></div>    
					</div>';
					$pageCounter++;
				}
			}

			$body .= '</div></div>';

			$p = $response->setResponse($body);
			return $p;
		}

		private function validation(array $params): bool
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
	}
