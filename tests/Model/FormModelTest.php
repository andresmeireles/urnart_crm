<?php declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\FormModel;
use PHPUnit\Framework\TestCase;
use App\Utils\Andresmei\FlashResponse;
use App\Tests\TestTrait;

class FormModelTest extends TestCase
{
    use TestTrait;

    public function testSaveReport()
    {
        $entityManager = $this->getTestManager();
        $formModel = new FormModel($entityManager);
        $data = [];
        $path = 'path';
        $result = $formModel->saveReport($data, $path);

        $this->assertEquals(new FlashResponse(200, 'success', 'Arquivo salvo com sucesso!'), $result);
    }

    public function testRunAccountabilityReport()
    {
        $em = $this->getTestManager();
        $model = new FormModel($em);
        $data = array(
            "driver-name" => "Andre",
            "dt-out" => "30-04-2019",
            "dt-in" => "03-05-2019",
            "km-out" => "150",
            "km-in" => "250",
            "customerArr0" => [
                "customer" => "c",
                "freight" => "390",
                "order-value" => "",
                "check" => "",
                "other" => ""
            ],
            "customerArr1" => [
                "customer" => "d",
                "freight" => "890",
                "null" => "R$ 0,00",
                "order-value" => "0",
                "check" => "0",
                "other" => "0"
            ],
            "despesas0" => [
                "name" => "ze",
                "value" => "90"
            ],
            "caixa" => "1190"
        );
        $result = $model->runAccountabilityReport($data);
        $this->assertEquals(new FlashResponse(200, 'success', 'Relatorio criado com suceeso.'), $result);
    }
}
