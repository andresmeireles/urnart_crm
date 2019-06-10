<?php declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\FormModel;
use PHPUnit\Framework\TestCase;
use App\Utils\Andresmei\FlashResponse;
use App\Tests\TestTrait;
use App\Entity\TravelAccountability;

class FormModelTest extends TestCase
{
    use TestTrait;

    public function createFormMock()
    {
        $mockEm = $this->getTestEntityManager();
        $model = new FormModel($mockEm);

        return $model;
    }

    public function testSaveReport()
    {
        $entityManager = $this->getTestManager();
        $formModel = new FormModel($entityManager);
        $data = [];
        $path = 'path';
        $result = $formModel->saveReport($data, $path);

        $this->assertEquals(new FlashResponse(200, 'success', 'Arquivo salvo com sucesso!'), $result);
    }

    public function testReportResolver()
    {
        $model = $this->createFormMock();
        $data = [
            "driver-name" => 'Andre',
            "dt-out" => '10-06-2019',
            "dt-in" => '10-06-2019',
            "km-out" => '300',
            "km-in" => '300',
            "customerArr0" => [
                "customer" => 'ZE',
                "freight" => '30',
                "order-value" => '30',
                "check" => '30',
                "other" => '30'
            ],
            "comment" => 'Ze',
            "despesas0" => [
                "name" => 'Balão',
                "value" => '30'
            ],
            'caixa' => '60'
        ];
        $result = $model->runAccountabilityReport($data);

        $this->assertEquals(new FlashResponse(200, 'success', 'Relatorio criado com suceeso.'), $result);
    }

    public function testEditTravelExpenseReport()
    {
        $model = $this->createFormMock();
        $data = [
            [
                "name" => 'Balão',
                "value" => '30'
            ]
        ];
        $result = $model->editTravelExpenseReport($data, new TravelAccountability);

        $this->assertEquals('', $result);
    }

    public function testEditTravelEntryReport()
    {
        $model = $this->createFormMock();
        $data = [
            [
                "customer" => 'ZE',
                "freight" => '30',
                "order-value" => '30',
                "check" => '30',
                "other" => '30'
            ]
        ];
        $result = $model->editTravelEntryReport($data, new TravelAccountability);
        
        $this->assertEquals('', $result);
    }

    public function testEditAcountabilityReport()
    {
        $enity = new TravelAccountability;
        $model = $this->createFormMock();
        $data = [
            "driver-name" => 'Andre',
            "dt-out" => '10-06-2019',
            "dt-in" => '10-06-2019',
            "km-out" => '300',
            "km-in" => '300',
            "customerArr0" => [
                "customer" => 'ZE',
                "freight" => '30',
                "order-value" => '30',
                "check" => '30',
                "other" => '30'
            ],
            "comment" => 'Ze',
            "despesas0" => [
                "name" => 'Balão',
                "value" => '30'
            ],
            'caixa' => '60'
        ];
        $result = $model->editAcountabilityReport($enity, $data);

        $this->assertEquals(
            new FlashResponse(
                200,
                'success',
                sprintf(
                    'Relatório %s foi editado com sucesso.',
                    $enity->getId()
                )
            ),
            $result
        );
    }
}
