<?php 
namespace App\Utils\Generic;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class GenericSetter
{
	/**
	 * Entity Manager
	 * @object
	 */
	private $em;
	private $entity;
	private $typeMessage = 'success';
	private $devMessage;
	private $userMessage;

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
		$this->userMessage = 'Item cadastrado com sucesso';
	}

	public function set(string $entity, array $parameters): void
	{
		$this->setEntity($entity);
		$this->setFields($parameters);
		try {
			$this->save();	
		} catch (UniqueConstraintViolationException $e) {
			$this->userMessage = 'Item já cadastrado';
			$this->devMessage = $e->getMessage();
			$this->typeMessage = 'danger';
		}
	}

	public function getTypeMessage(): string
	{
		return $this->typeMessage;
	}

	public function getMessage(): string
	{
		return $this->userMessage;
	}

	public function getDevMessage(): string
	{
		return $this->devMessage;
	}
}
