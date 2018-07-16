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

		$pageCounter = 0;

		$body = '
		<style>
		img { max-width: 100%; max-height: 100%; }

		.top { margin-bottom: 10px;}
		.fit { display: inline-block }	
		.tag {width: 84%;}
		.img { width: 23%;}
		.clear { clear: both; }
		.right { float: right }
		.left { float: left }
		.font-sz0 { font-size: 45px }
		.font-sz1 { font-size: 40px }
		.font-sz2 { font-size: 37px }
		.c-0 { width: 15%; height: 4%}
		.c-1 { width: 58%; height: 4%; margin-left: 10px;}
		.c-2 { width: 20%; height: 5%}
		.cb-0 { width: 15%; height: 2%}
		.cb-1 { width: 58%; height: 2%; margin-left: 10px}
		.cb-2 { width: 20%; height: 2%}
		.center { text-align: center }
		.bottom .clear { border-bottom: 1px solid black; padding-top: 6px;}
		</style>

		<style media="print">
			#page { width: 21cm; height:30.7cm; padding: 0px 10px 0px 10px; margin: 0px 0px 39px 0px;}
		img { max-width: 100%; max-height: 100%; }

		.top { margin-bottom: 15px; margin-top: 10px;}
		.fit { display: inline-block }	
		.tag {width: 100%; margin: 10 0 17 0;}
		.img { width: 23%;}
		.clear { clear: both; }
		.right { float: right }
		.left { float: left }
		.font-sz0 { font-size: 50px }
		.font-sz1 { font-size: 38px }
		.font-sz3 { font-size: 35px }
		.font-sz2 { font-size: 35px }
		.c-0 { width: 12%; height: 10%}
		.c-1 { width: 65%; height: 10%; margin-left: 10px}
		.c-2 { width: 15%; height: 10%}
		.cb-0 { width: 12%; height: 8%}
		.cb-1 { width: 58%; height: 8%; margin-left: 10px}
		.cb-2 { width: 20%; height: 8%}
		.center { text-align: center }
		.bottom .clear { border-bottom: 1px solid black; padding-top: 6px;}
		</style>

		<div id="page">';

		foreach ($parameters as $param) {

			for ($c = 0; $c < $param['amount']; $c++) {
				$count = $c;
				$count++;

				if ($pageCounter == 8) {
					$body .= '</div><div id="page">';
					$pageCounter = 0;
				} 

				$body .= '<div class="tag">
				<div class="top">
				<div class="img left c-0">
				<img src="'. $image .'">
				</div>
				<div class="left c-1"><span class="'. ( (strlen($param['name']) > 27) ? "font-sz3" : "font-sz1") .'"><b>'. $param['name'] .'</b></span></div>
				<div class="right c-2 center"><span class="font-sz0">'. ( $param['check'] != 0 ? ceil($count/2) : $count ) .'</span></div>
				</div>
				<div class="clear"></div>
				<div class="bottom">
				<div class="left cb-0">&nbsp;</div>
				<div class="left cb-1"><span class="font-sz2">'. $param['city'] .'</span></div>
				<div class="right center cb-2"><span class="font-sz2">VOL. '. ( $param['check'] != 0 ? ceil($param['amount']/2) : $param['amount']) .'</span></div>    
				<div class="clear"></div>    
				</div>
				</div>';
				$pageCounter++;
			}
		}

		$body .= '</div></div>';

		return 	$response->setResponse($body);
	}
}
