<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Product;

use App\Actions\Product\CreateColor;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateColorTest extends TestCase
{
    use RefreshDatabase;

    private CreateColor $action;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->action = new CreateColor();
    }

    public function testCreate(): void
    {
        $result = $this->action->create('blue', $this->user);
        self::assertIsObject($result);
    }

    public function testId(): void
    {
        $result = $this->action->create('blue', $this->user);
        self::assertSame(1, $result->id);
    }

    public function testRepeatedName(): void
    {
        $this->expectException(QueryException::class);
        $this->action->create('blue', $this->user);
        $this->action->create('blue', $this->user);
        // self::assertSame(1, $result->id);
    }
}
