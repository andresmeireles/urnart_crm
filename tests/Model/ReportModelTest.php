<?php declare(strict_types = 1);

namespace App\Tests\Model;

use PHPUnit\Framework\TestCase;
use App\Tests\TestTrait;
use App\Model\ReportModel;

class ReportModelTest extends TestCase
{
    use TestTrait;

    public function createMockObject()
    {
        $mockEm = $this->getTestEntityManager();
        $mockedObject = new ReportModel($mockEm);

        return $mockedObject;
    }
}