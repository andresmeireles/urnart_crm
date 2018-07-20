<?php 
namespace App\Utils\Generic;

use Doctrine\ORM\EntityManagerInterface;

class GenericSetter
{
	/**
	 * Entity Manager
	 * @object
	 */
	private $em;
	private $entity;

	function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	public function setEntity(string $entity): self
	{
		$className = 'App\Entity\\'.ucfirst($entity);
		if (class_exists($className)) {
			$this->entity = new $className;
			return $this;
		}

		throw new \Exception('Classe '. $entity .' não existe em App\Entity');
	}

	public function setFields(array $parameters): self
	{
		foreach ($parameters as $field => $value) {
			$setterFunction = 'set'.ucfirst($field);
			$this->entity->$setterFunction($value);
		}

		return $this;
	}

	public function save(): void
	{
		$elements = $this->entity->__toArray();
		$counter = 0;

		foreach ($elements as $key => $value) {
			if ($value == null) {
				$counter++;
			}
		}

		if ($counter == count($elements)) {
			throw new \Exception('Não é possível persistir elemento completamente vázio');
		}

		$this->em->persist($this->entity);
		$this->em->flush();
	}

	public function set(string $entity, array $parameters)
	{
		$this->setEntity($entity);
		$this->setFields($parameters);
		$this->save();
	}
}
