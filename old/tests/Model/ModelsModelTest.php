<?php declare(strict_types = 1);

namespace App\Tests\Model;

use App\Model\ModelsModel;
use App\Tests\TestTrait;
use PHPUnit\Framework\TestCase;

class ModelsModelTest extends TestCase
{
    use TestTrait;

    public function testGetModelPrices()
    {
        $entityManager = $this->getTestEntityManager();
        $model = new ModelsModel($entityManager);
        $result = $model->getModelPrices();
        $this->assertEquals([], $result);
    }
}
