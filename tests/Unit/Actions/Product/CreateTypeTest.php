<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Product;

use App\Actions\Product\CreateColor;
use App\Actions\Product\CreateType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTypeTest extends TestCase
{
    use RefreshDatabase;

    private CreateType $action;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->action = new CreateType($user);
    }

    public function testCreate(): void
    {
        $result = $this->action->create('blue');
        self::assertIsObject($result);
    }

    public function testId(): void
    {
        $result = $this->action->create('blue');
        self::assertSame(1, $result->id);
    }
}
