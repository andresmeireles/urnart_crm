<?php declare(strict_types = 1);

namespace App\Tests\Model;

use App\Entity\Unit;
use App\Tests\TestTrait;
use App\Entity\Feedstock;
use App\Model\FeedstockModel;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use App\Entity\FeedstockInventory;
use Doctrine\Common\Persistence\ObjectRepository;

class FeedstockModelTest extends TestCase
{
    use TestTrait;

    /**
     * @return FeedstockModel
     */
    private function createFeedstock(): FeedstockModel
    {
        $mockEm = $this->getTestEntityManager();
        $feedstock = new FeedstockModel($mockEm);

        return $feedstock;
    }

    public function testPersistInsert()
    {
        $model = $this->createFeedstock();
        $data = [
            'name' => 'Fred',
            'mainVendor' => 'Fred',
            'unit' => 'UN',
            'departament' => 'Fred',
            'maxStock' => '69',
            'minStock' => '69',
            'periocid' => '15',
            'description' => 'overlord'
        ];
        $result = $model->persist($data, 'insert');

        $this->assertEquals('', $result);
    }

    public function testUpdate()
    {
        $mockEntMan = $this->createMock(EntityManager::class);
        $mockRepo = $this->createMock(ObjectRepository::class);
        
        $mockEntMan->expects($this->any())
            ->method('getRepository')
            ->willReturn($mockRepo);

        $mockRepo->expects($this->at(0))
            ->method('find')
            ->willReturn(new Feedstock);

        $mockRepo->expects($this->at(1))
            ->method('find')
            ->willReturn(new Unit);

        $mockRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn(new FeedstockInventory);

        $feedstock = new FeedstockModel($mockEntMan);
        $data = [
            'name' => 'Fred',
            'mainVendor' => 'Fred',
            'unit' => 'UN',
            'departament' => 'Fred',
            'maxStock' => '69',
            'minStock' => '69',
            'periocid' => '15',
            'description' => 'overlord',
            'feedstock_id' => 82
        ];
        $result = $feedstock->update($data, 82);

        $this->assertEquals('', $result);
    }

    public function testFeedIn()
    {
        $data = [
            'date' => '02/02/2002',
            0 => [
                'name' => 82,
                'amount' => 92
            ]
        ];

        $mockEntMan = $this->createMock(EntityManager::class);
        $mockRepo = $this->createMock(ObjectRepository::class);
        
        $mockEntMan->expects($this->any())
            ->method('getRepository')
            ->willReturn($mockRepo);
        $mockRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn(new FeedstockInventory);
        
        $feedstock = new FeedstockModel($mockEntMan);
        $result = $feedstock->feedIn($data);

        $this->assertEquals('', $result);
    }

    public function testFeedOut()
    {
        $data = [
            'date' => '02/02/2002',
            0 => [
                'name' => 82,
                'amount' => 0
            ]
        ];

        $mockEntMan = $this->createMock(EntityManager::class);
        $mockRepo = $this->createMock(ObjectRepository::class);
        
        $mockEntMan->expects($this->any())
            ->method('getRepository')
            ->willReturn($mockRepo);
        $mockRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn(new FeedstockInventory);
        
        $feedstock = new FeedstockModel($mockEntMan);
        $result = $feedstock->feedOut($data);

        $this->assertEquals(['http_code' => 200, 'message' => 'Sucesso!'], $result);
    }

    public function testFeedOutFail()
    {
        $data = [
            'date' => '02/02/2002',
            0 => [
                'name' => 82,
                'amount' => 1
            ]
        ];

        $mockEntMan = $this->createMock(EntityManager::class);
        $mockRepo = $this->createMock(ObjectRepository::class);
        
        $mockEntMan->expects($this->any())
            ->method('getRepository')
            ->willReturn($mockRepo);
        $mockRepo->expects($this->any())
            ->method('findOneBy')
            ->willReturn(new FeedstockInventory);
        
        $feedstock = new FeedstockModel($mockEntMan);
        $result = $feedstock->feedOut($data);

        $this->assertEquals(['http_code' => 203, 'message' => 'Retirada maior que estoque'], $result);
    }
}
