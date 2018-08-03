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

		foreach ($parameters as $param) {
			ValidatorJson::validate($param, [
				'name' => v::notEmpty(),
				'city' => v::optional(v::notEmpty()),
				'amount' => v::not(v::negative())->notEmpty(),
				'check' => v::optional(v::boolVal())
			]);
		}

		$response = new ResponseForm();

		$image = file_get_contents(__DIR__.'/etiqueta/logo');

		$pageCounter = -1;

		$body = '
		<style>
		.tag-page1 { width: 25cm; margin: 0px 0px 80px 0px;}

		.s-tag {margin: 5px 0px 0px 0px;}
		.i-tag {margin: 0px 0px 0px 0px;}

		.s-tag div {margin: 0px 15px 0px 0px;}
		.i-tag div {margin: 0px 15px 0px 0px;}

		.tag-img { max-width: 100%; max-height: 100%: }
		.tag-box-1 { width: 3cm; height: 4.5cm; padding-top: 20px; overflow: hidden }
		.tag-box-2 { width: 17cm; height: 4.5cm; overflow: hidden}
		.tag-box-3 { width: 3cm; height: 4.5cm; overflow: hidden}
		.tag-box-4 { width: 14cm; overflow: hidden; }
		.tag-box-5 { width: 3cm; overflow: hidden; }
		.tag-box-6 { width: 3cm; overflow: hidden; }

		.tag-center { text-align: center }

		.tag-float-right { float: right; }
		.tag-float-left { float: left; }
		.tag-clear { clear: both; width: 100%}

		.t-right { padding: 0px 0px 0px 10px; } 

		.t-font-sz0 { font: 40px Arial, Serif; padding-top: 2px; }
		.t-font-sz1 { font: bold 80px Arial, Serif; }
		.t-font-sz3 { font: bold 40px Arial, Serif; padding-top: 10px } 
		.t-font-sz4 { font: bold 30px Arial, Serif; padding-top: 10px }
		.t-font-sz5 { font: 45px Arial, Serif; padding: 7px 0px 0px 0px; }
		.t-font-sz6 { font: 65px Arial, Serif; padding-top: 7px; }

		.t-border { border-bottom: 1px solid black}

		.t-w100 { width: 100% }
		</style>

		<div class="tag-page1">';

		foreach ($parameters as $param) {

			for ($c = 0; $c < $param['amount']; $c++) {
				$count = $c;
				$count++;

				if ($pageCounter == 6) {
					$body .= '</div><div id="tag-page1">';
					$pageCounter = 0;
				} 

				$body .= '<div class="tag">
				<div class="s-tag t-border">
				
				<div class="tag-box-1 tag-float-left"><img class="tag-img" src="'. $image .'"></div>
				
				<div class="tag-box-2 tag-float-left">
				<span class="t-w100 tag-float-left '. ( $param['city'] == '' ? "t-font-sz6" : ( (strlen($param['name']) > 27) ? "t-font-sz0" : "t-font-sz5" ) )  .'">'. $param['name'] .'</span>
				<span class="tag-float-left t-font-sz3 t-w100">'. $param['city'] .'</span>
				</div>
				
				<div class="tag-box-3 tag-float-left t-right">
				<span class="t-font-sz1 tag-float-left t-w100">'. ( ($param['check'] != 0) ? ceil($count/2) : $count ) .'</span>
				<span class="t-font-sz4 tag-float-left t-w100">VOL. '. ( ($param['check'] != 0) ? ceil($param['amount']/2) : $param['amount']) .'</span>
				</div> 
				<div class="tag-clear"></div>
				
				</div>
				</div>';

				$pageCounter++;
			}
		}

		$body .= '</div></div>';

		return 	$response->setResponse($body);
	}
}
