<?php

declare(strict_types=1);

namespace Tests\Unit\Actions\Product;

use App\Actions\Product\Create;
use App\Models\Color;
use App\Models\Model;
use App\Models\Type;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTest extends TestCase
{
    use RefreshDatabase;

    private Create $action;

    protected function setUp(): void
    {
        parent::setUp();
        $user = User::factory()->create();
        $this->action = new Create($user);
    }

    public function testCreate(): void
    {
        $result = $this->action->create(
            model: Model::factory()->create(),
            type: Type::factory()->create(),
            color: Color::factory()->create(),
            price: 206.00,
            height: '190'
        );

        self::assertIsObject($result);
    }

    public function testCreateWithNoRegisteredModel(): void
    {
        $this->expectException(\TypeError::class);
        $result = $this->action->create(
            model: Model::find(1),
            type: Type::factory()->create(),
            color: Color::factory()->create(),
            price: 206.00,
            height: '190'
        );

        self::assertIsObject($result);
    }
}
