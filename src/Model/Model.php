<?php declare(strict_types=1);
namespace App\Model;

use \Doctrine\Common\Persistence\ObjectManager;

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

    public function __construct(ObjectManager $entityManager)
    {
        $this->em = $entityManager;
    }
}