<?php 
namespace App\Utils\Generic;

use Doctrine\ORM\EntityManagerInterface;

abstract class GenericContainer
{
    
	/**
	 * Entity Manager
	 * @object
	 */
	protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
}
