<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Delivery;

use App\Actions\Delivery\Create;
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
        $result = $this->create->create('Barco', User::first(), true);
        self::assertIsObject($result);
    }

    public function testCheck(): void
    {
        $result = $this->create->create('Barco', User::first(), true);
        self::assertSame('Barco', $result->name);
    }
}
