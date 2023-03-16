<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Customer;

use App\Actions\Customer\Create;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    private Create $create;

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create();
        $this->create = new Create();
    }

    public function testCreate(): void
    {
        $u = User::first();
        $result = $this->create->create($u, 'Andre', 'Andre corp', 1);
        self::assertIsObject($result);
    }

    public function testCheckNumber(): void
    {
        $u = User::first();
        $result = $this->create->create($u, 'Andre', 'Andre corp', 1);
        self::assertSame(1, $result->number);
    }

    public function testWithoutNumber(): void
    {
        $u = User::first();
        $result = $this->create->create($u, 'Andre', 'Andre corp');
        self::assertSame(1, $result->number);
    }

    public function testAddMultipleWithoutNumber(): void
    {
        $u = User::first();
        $this->create->create($u, 'Andre', 'Andre corp');
        $result = $this->create->create($u, 'Andre', 'Andre corp');
        self::assertSame(2, $result->number);
    }

    public function testAddAfterAnAddedNumber(): void
    {
        $u = User::first();
        $this->create->create($u, 'Andre', 'Andre corp', 100);
        $result = $this->create->create($u, 'Andre', 'Andre corp');
        self::assertSame(101, $result->number);
    }

    public function testTradeName(): void
    {
        $u = User::first();
        $result = $this->create->create($u, 'Andre', 'Andre corp');
        self::assertSame('Andre', $result->trade_name);
    }

    public function testCompanyName(): void
    {
        $u = User::first();
        $result = $this->create->create($u, 'Andre', 'Andre corp');
        self::assertSame('Andre corp', $result->company_name);
    }
}
