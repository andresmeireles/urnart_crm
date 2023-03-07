<?php

declare(strict_types=1);

namespace App\Actions\Api\Product;

use App\Models\User;

/**
 * @template T
 */
interface CreateInterface
{
    /**
     * Create function
     *
     * @param string $name
     * @return T
     */
    public function create(string $name, User $user);
}
