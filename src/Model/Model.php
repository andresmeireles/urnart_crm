<?php declare(strict_types=1);
namespace App\Model;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Service Container for models
 */
abstract class Model
{
    /**
     * Entity Manger
     *
     * @var object
     */
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }
}