<?php declare(strict_types = 1);

namespace App\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

abstract class GenericContainer
{
    /**
     * Entity Manager
     *
     * @var EntityManagerInterface
     */
    protected $entityManagerm;

    /**
     * @var  Environment
     */
    protected $twig;

    public function __construct(EntityManagerInterface $entityManager, Environment $twig)
    {
        $this->entityManagerm = $entityManager;
        $this->twig = $twig;
    }
}
