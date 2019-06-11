<?php declare(strict_types=1);

namespace App\Tests\Model;

use PHPUnit\Framework\TestCase;
use App\Utils\Andresmei\FlashResponse;
use App\Tests\TestTrait;
use App\Model\ReportModel;
use App\Entity\ManualOrderReport;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ObjectRepository;
use App\Utils\Exceptions\CustomException;

class ReportModelTest extends TestCase
{
    use TestTrait;

    public function createMockObject()
    {
        $mockEm = $this->getTestEntityManager();
        $mockedObject = new ReportModel($mockEm);

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
        $model = new ReportModel($entityManager);
        $reportData = [
            'driverName' => '',
            'kmout' => null,
            'departureDate' => '2010/12/31'
        ];
        $orders = [10];
        $result = $model->createTruckDepartureReport($reportData, $orders);
        
        $this->assertEquals(new FlashResponse(200, 'success', 'Relatorio de saida do caminhão criado com sucesso!'), $result);
    }
}