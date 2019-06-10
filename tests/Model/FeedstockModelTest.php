<?php declare(strict_types=1);

namespace App\Tests\Model;

use PHPUnit\Framework\TestCase;
use App\Tests\TestTrait;
use App\Model\FeedstockModel;

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
        $entityManager = $this->createMock(EntityManager::class);
        $entityManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($this->getTestRepository($entity));
        $feedstock = new FeedstockModel($mockEm);
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
        $result = $feedstock->update($data, 82);

        $this->assertEquals('', $result);
    }
}
