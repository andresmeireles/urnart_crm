<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Product;

use App\Actions\Product\CreateColor;
use App\Actions\Product\CreateSpec;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateSpecTest extends TestCase
{
    use RefreshDatabase;

    private CreateSpec $action;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
        $this->action = new CreateSpec();
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
}
