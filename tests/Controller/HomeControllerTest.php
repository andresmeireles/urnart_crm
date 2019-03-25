<?php

namespace App\Tests\Controller;

use App\Model\ListModel;
use PHPUnit\Framework\TestCase;
use App\Controller\HomeController;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Response;

class HomeControllerTest extends TestCase
{
    public function testGreeting()
    {
        $greet = new HomeController();
        $prop = 'ninja';
        $result = $greet->greeting($prop);

        $this->assertEquals(new Response(sprintf('Olá %s', $prop ?? 'meu amigo')), $result);
    }

    public function testIndex()
    {
        $index = new HomeController();
        $objectManager = $this->createMock(ObjectManager::class);
        $objectManager->expects($this->any())
            ->method('getRepository')
            ->willReturn($employeeRepository);
            
        $model = new ListModel($objectManager);
        $result = $index->index($model);

        $this->assertEquals(new Response(), $result);
    }
}
