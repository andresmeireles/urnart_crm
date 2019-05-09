<?php declare(strict_types=1);

namespace App\Tests\Model;

use PHPUnit\Framework\TestCase;
use App\Tests\TestTrait;
use App\Model\ReportModel;
use App\Entity\ManualOrderReport;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;

class ReportModelTest extends TestCase
{
    use TestTrait;

    public function testSetTruckOrder()
    {
        $order = new ManualOrderReport();
        $em = $this->getTestManager();
        $orderRepository = $this->createMock(ObjectRepository::class);
        $orderRepository->expects($this->any())
            ->method('find')
            ->willReturn($order);

        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
                ->willReturn($orderRepository);
        
                $model = new ReportModel($em);
        $result = $model->setTruckOrder(30);
        $this->assertEquals(true, $result);
    }
}
