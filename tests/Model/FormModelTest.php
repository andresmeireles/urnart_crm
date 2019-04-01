<?php
declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\FormModel;
use PHPUnit\Framework\TestCase;
use App\Utils\Andresmei\FlashResponse;

class FormModelTest extends TestCase
{
    public function testSaveReport()
    {
        $formModel = new FormModel();
        $data = [];
        $path = 'path';
        $result = $formModel->saveReport($data, $path);

        $this->assertEquals(new FlashResponse(200, 'success', 'Arquivo salvo com sucesso!'), $result);
    }
}
