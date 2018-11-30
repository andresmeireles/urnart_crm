<?php
namespace App\Utils;

use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;
use Knp\Snappy\Pdf;

abstract class GenericContainer
{
    
    /**
     * Entity Manager
     *
     * @var = object
     */
    protected $em;

    protected $twig;

    protected $pdf;

    public function __construct(EntityManagerInterface $em, Environment $twig)
    {
        $this->em = $em;
        $this->twig = $twig;
    }
}
