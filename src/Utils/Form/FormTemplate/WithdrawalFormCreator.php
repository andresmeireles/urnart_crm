<?php

namespace App\Utils\Form\FormTemplate;

use App\Utils\Form\Parameters;
use App\Utils\Validation\ValidatorJson;
use Respect\Validation\Validator as v;
use App\Utils\Form\Interfaces\CreateFormInterface;
use App\Utils\Form\Interfaces\ResponseFormInterface;
use App\Utils\Form\ResponseForm;

class WithdrawalFormCreator implements CreateFormInterface
{
    public function createForm(Parameters $parameters): ResponseFormInterface
    {
        $clonedParams = $parameters->getClonedParameters();
        $nonCloned = $parameters->getNonClonedParameters();

        ValidatorJson::validate($clonedParams, [
            'amount' => v::notEmpty()->numeric()->positive()->noWhiteSpace(),
            'prodName' => v::notEmpty()->alpha()
        ]);

        ValidatorJson::validate($nonCloned, [
            'clientName' => v::notEmpty()->alpha()
        ]);

        if (ValidatorJson::getErrors()) {
            $response = ValidatorJson::getErrors();
            return new ResponseForm($response);
        }

        $response = $this->createBody($parameters);
        return new ResponseForm($response);
    }

    private function createBody (Parameters $parameters): string 
    {
        $clonedParams = $parameters->getClonedParameters();
        $nonCloned = $parameters->getNonClonedParameters();

        $body = '
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function () {
			    window.print();
			});
        </script>
        <style>
        * { font: 18px "Arial";}
        #page { height: 21cm;  width: 29.7cm; border: 1px solid black }
        #half-page { width: 29cm; height: 10cm; margin:10px;}
        .center { text-align: center }
        
        .table { width: 100%; border: 2px solid black}
        
        #head th,
        #bhead th,
        #footer td,
        #observation td { border-top: 2px solid black; border-bottom: 2px solid black; border-left: 1px solid}
        
        #body td,
        #spacer td { border-bottom: 1px solid black; border-left: 1px solid }
        </style>
        
        <div id="page">
        <div id="half-page">
          <div class="center">Autorização de Retirada de Urnas</div>
          <table cellspacing=0 class="table">
            <tr id="head">
              <th>Cliente</th>
              <th>'.$nonCloned['clientName'] .'</th>
              <th>Data</th>
              <th>10/10/2010</th>
            </tr>
            <tr id="bHead">
              <th>Quantidade</th>
              <th colspan=3>PRODUTO</th></th>
            </tr>
            <tr id="body">';

            foreach ($clonedParams as $param) {
                $body .= '<td class="center">'. $param['amount'] .'</td>
                <td colspan=3 class="center">'. $param['prodName'] .'</td>';
            }
            
            $body .= '</tr>
            <tr id="spacer">
              <td class="center">&nbsp;</td>
              <td colspan=3 class="center">&nbsp;</td>
            </tr>
            <tr  id="footer">
              <td class="center">1</td>
              <td colspan=3 class="center">Ass:____________________________________________</td>
            </tr>
            <tr id="observation">
              <td class="center">Observação</td>
              <td class="center" colspan=3>'. $nonCloned['observation'] .'</td>
            </tr>
          </table>
        </div>  
        </div>';

        return $body;
    }
}