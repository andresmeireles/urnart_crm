<?php
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
    protected $em;

    /**
     * @var  Environment
     */
    protected $twig;

    public function __construct(EntityManagerInterface $em, Environment $twig)
    {
        $this->em = $em;
        $this->twig = $twig;
    }
}
