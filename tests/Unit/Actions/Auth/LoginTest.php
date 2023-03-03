<?php

declare(strict_types=1);

namespace Tests\Unit\Auth;

use App\Actions\Api\Auth\LoginInterface;
use App\Actions\Auth\Login;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private LoginInterface $action;

    protected function setUp(): void
    {
        parent::setUp();
        User::factory()->create([
            'name' => 'andre',
            'email' => 'andre@email.com',
            'email_verified_at' => now(),
            'password' => Hash::make('123'), // password
        ]);
        $this->action = new Login();
    }

    public function testLogin(): void
    {
        $result = $this->action->withUserAndPassword('andre', '123', false);
        // dd($result);
        self::assertIsString($result);
    }

    public function testNotFound(): void
    {
        $this->expectException(ModelNotFoundException::class);
        $result = $this->action->withUserAndPassword('Andre', '123', false);
        self::assertIsString($result);
    }

    public function testWrongPassword(): void
    {
        $this->expectException(Exception::class);
        $result = $this->action->withUserAndPassword('andre', '1234', false);
        self::assertIsString($result);
    }

    public function testTokenIsCreated(): void
    {
        $this->action->withUserAndPassword('andre', '123', false);
        $user = User::first();
        $result = $user->tokens()->get()->count();
        self::assertSame(1, $result);
    }

    public function testTokenValid(): void
    {
        $this->action->withUserAndPassword('andre', '123', false);
        $user = User::first();
        /** @var \Laravel\Sanctum\PersonalAccessToken */
        $result = $user->tokens()->first();
        self::assertIsObject($result->getAttribute('expires_at'));
    }
}
