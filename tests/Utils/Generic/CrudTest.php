<?php declare(strict_types = 1);

namespace App\Tests\Utils\Generic;

use PHPUnit\Framework\TestCase;
use App\Utils\Andresmei\FlashResponse;
use App\Tests\TestTrait;
use App\Utils\Generic\Crud;

class CrudTest extends TestCase
{
    use TestTrait;

    /**
     * @return Crud
     */
    private function createCrud(): Crud
    {
        $mockedEm = $this->getTestEntityManager();
        $crud = new Crud($mockedEm);

        return $crud;
    }

    public function testRemove()
    {
        // $entityManager = $this->getTestManager();
        $mockedEm = $this->getTestEntityManager();
        $crud = new Crud($mockedEm);
        $result = $crud->remove('82', 'ManualOrderReport');
        $this->assertEquals(new FlashResponse(200, 'success', sprintf('Produto %d removido com sucesso!', '82')), $result);
    }

    public function testCommit()
    {
        $mockedEm = $this->getTestEntityManager();
        $crud = new Crud($mockedEm);
        $result = $crud->commit('isso ai!');

        $this->assertEquals('', $result);
    }

    public function testGet()
    {
        $mockedEm = $this->getTestEntityManager();
        $crud = new Crud($mockedEm);
        $result = $crud->get('ManualProductOrder');

        $this->assertEquals([], $result);
    }

    public function testGetWithJsonParam()
    {
        $mockedEm = $this->getTestEntityManager();
        $crud = new Crud($mockedEm);
        $result = $crud->get('ManualProductOrder', 'json');

        $this->assertEquals([], $result);
    }

    public function testGetJsonData()
    {
        $mockedEm = $this->getTestEntityManager();
        $crud = new Crud($mockedEm);
        $result = $crud->getJsonData('ManualProductOrder');

        $this->assertEquals('[]', $result);
    }

    public function testGetWithSimpleCriteria()
    {
        $crud = $this->createCrud();
        $result = $crud->getWithSimpleCriteria('ManualProductOrder', ['id' => 82]);

        $this->assertEquals([], $result);
    }

    public function testGetWithSimpleCriteriaJson()
    {
        $crud = $this->createCrud();
        $result = $crud->getWithSimpleCriteriaJson('ManualProductOrder', ['id' => 82]);

        $this->assertEquals('[]', $result);
    }
}