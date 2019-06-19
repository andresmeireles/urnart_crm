<?php declare(strict_types=1);

namespace App\Tests\Model;

use App\Tests\TestTrait;
use PHPUnit\Framework\TestCase;
use App\Model\DepartureModel;
use App\Entity\TravelTruckOrders;
use App\Utils\Andresmei\Form;

class DepartureModelTest extends TestCase
{
    use TestTrait;

    public function getModel()
    {
        $entityManager = $this->getTestEntityManager();
        $model = new DepartureModel($entityManager);

        return $model;
    }

    public function getTestForm()
    {
        return $this->createMock(Form::class);
    }

    public function testExportAllPdfReports()
    {
        $entityManager = $this->getTestEntityManager();
        // $form = new Form($entityManager, $twig, new \App\Config\NonStaticConfig);
        /** @var Form $form */
        $form = $this->createMock(Form::class);
        $form->parsedFile = '';
        $entityObject = new TravelTruckOrders();
        $entityObject->setDriverName('josé');
        $model = new DepartureModel($entityManager);
        $result = $model->exportAllPdfReports($entityObject, $form);

        $this->assertEquals('C:\Projects\sysadmin\src\Model/../../public/reportBuilder/relatorio.zip', $result);
    }

    public function testGenerateAutomaticReportWithDataTag()
    {
        $entity = new TravelTruckOrders;
        /** @var Form $form */
        $form = $this->getTestForm();
        $model = $this->getModel();
        $result = $model->generateAutomaticReportWithData($entity, $form, 'tag', 'show');

        $this->assertEquals([], $result);
    }

    public function testGenerateAutomaticReportWithDataFreightletter()
    {
        $entity = new TravelTruckOrders;
        /** @var Form $form */
        $form = $this->getTestForm();
        $model = $this->getModel();
        $result = $model->generateAutomaticReportWithData($entity, $form, 'freightletter', 'show');

        $this->assertEquals([], $result);
    }

    public function testGenerateAutomaticReportWithDataReceipt()
    {
        $entity = new TravelTruckOrders;
        /** @var Form $form */
        $form = $this->getTestForm();
        $model = $this->getModel();
        $result = $model->generateAutomaticReportWithData($entity, $form, 'receipt', 'show');

        $this->assertEquals([], $result);
    }

    public function testGenerateAutomaticReportWithDataRb()
    {
        $entity = new TravelTruckOrders;
        /** @var Form $form */
        $form = $this->getTestForm();
        $model = $this->getModel();
        $result = $model->generateAutomaticReportWithData($entity, $form, 'rb', 'show');

        $this->assertEquals([], $result);
    }

    public function testGenerateAutomaticReportWithDataRomaneio()
    {
        $entity = new TravelTruckOrders;
        /** @var Form $form */
        $form = $this->getTestForm();
        $model = $this->getModel();
        $result = $model->generateAutomaticReportWithData($entity, $form, 'romaneio', 'show');

        $this->assertEquals([], $result);
    }

    public function testGenerateAutomaticReportWithDataTravel()
    {
        $entity = new TravelTruckOrders;
        $entity->setDriverName('Zeca');
        /** @var Form $form */
        $form = $this->getTestForm();
        $model = $this->getModel();
        $result = $model->generateAutomaticReportWithData($entity, $form, 'travel', 'show');

        $this->assertEquals([], $result);
    }
}
