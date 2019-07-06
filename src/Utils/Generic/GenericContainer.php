<?php declare(strict_types = 1);

namespace App\Utils\Generic;

use Doctrine\ORM\EntityManagerInterface;

abstract class GenericContainer
{
    /**
     * Entity Manager
     *
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
