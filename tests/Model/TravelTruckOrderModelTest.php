<?php declare(strict_types = 1);

namespace App\Tests\Model;

use App\Model\TravelTruckOrderModel;
use PHPUnit\Framework\TestCase;
use App\Utils\Andresmei\FlashResponse;
use App\Tests\TestTrait;
use App\Entity\ManualOrderReport;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Entity\TravelTruckOrders;

/**
 * Class TravelTruckOrderModelTest
 * @package App\Tests\Model
 */
final class TravelTruckOrderModelTest extends TestCase
{
    use TestTrait;

    public function createMockObject()
    {
        $mockEm = $this->getTestEntityManager();
        $mockedObject = new TravelTruckOrderModel($mockEm);

        return $mockedObject;
    }

    public function testCreateTruckDepartureReport()
    {
        /** @var PHPUnit\Framework\MockObject\MockObject|EntityManager $entityManager */
        $entityManager = $this->createMock(EntityManager::class);
        $mockRepo = $this->createMock(ObjectRepository::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($mockRepo);
        $mockRepo->expects($this->any())
            ->method('find')
            ->willReturn(new ManualOrderReport);
        $model = new TravelTruckOrderModel($entityManager);
        $reportData = [
            'driverName' => 'José',
            'kmout' => null,
            'departureDate' => '2010/12/31     '
        ];
        $orders = [
            ['id' => 10]
        ];
        $result = $model->createTruckDepartureReport($reportData, $orders);

        $this->assertEquals(
            new FlashResponse(200, 'success','Relatorio de saida do caminhão criado com sucesso!'),
            $result
        );
    }

    public function testEditTruckDepartureReport()
    {
        /** @var PHPUnit\Framework\MockObject\MockObject|EntityManager $entityManager */
        $entityManager = $this->createMock(EntityManager::class);
        $mockRepo = $this->createMock(ObjectRepository::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($mockRepo);
        $mockRepo->expects($this->at(0))
            ->method('find')
            ->willReturn(new TravelTruckOrders);
        $mockRepo->expects($this->at(1))
            ->method('find')
            ->willReturn(new ManualOrderReport);
        $model = new TravelTruckOrderModel($entityManager);
        $reportData = [
            'driverName' => 'José',
            'kmout' => null,
            'departureDate' => '2010/12/31     '
        ];
        $orders = [
            ['id' => 10]
        ];
        $result = $model->editTruckDepartureReport(new TravelTruckOrders, $reportData, $orders);

        $this->assertEquals(
            new FlashResponse(
                200,
                'success',
                sprintf('Relatorio %s editado com sucesso', (new TravelTruckOrders)->getId())
            ),
            $result
        );
    }
}