<?php

declare(strict_types=1);

namespace App\Actions\Api;

use App\Models\User;
use Doctrine\DBAL\Query\QueryException;

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
     *
     * @throws QueryException
     */
    public function create(string $name, User $user);
}
