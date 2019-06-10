<?php
declare(strict_types=1);

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/home');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    // public function testFinanceiro()
    // {
    //     $client = static::createClient();
    //     $crawler = $client->request('GET', '/');
    //     //$this->assertEquals(200, $client->getResponse()->getStatusCode());
    //     $this->assertGreaterThan(
    //         0,
    //         $crawler->filter('h1.text-center')->count()
    //     );
    // }
}
