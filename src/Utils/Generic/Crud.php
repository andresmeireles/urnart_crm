<?php 
namespace App\Utils\Generic;

use App\Utils\Generic\GenericSetter;
use App\Utils\Generic\GenericContainer;
use JMS\Serializer\SerializerBuilder;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class Crud extends GenericContainer
{
	/**
	 * Nome da entidade utilizada
	 *
	 * @var [string]
	 */
	private $entity;

	/**
	 * Mensagem de retorno da ação
	 *
	 * @var [string]
	 */
	private $userMessage;

	private $devMessage;

	private $typeMessage = 'success';

	use GenericSetter;

	public function setEntity(string $entity): void
	{
		$className = 'App\Entity\\'.ucwords($entity);
		$this->entity = $className;
	}

	public function getJsonData(string $entity)
	{
		$this->setEntity($entity);
		$serializer = SerializerBuilder::create()->build();

		$data = $this->em->getRepository($this->entity)->findAll();
		$jsonResponse = $serializer->serialize($data, 'json');

		return $jsonResponse;
	}

	public function get(string $entity, ?string $type = null): ?array
	{
		if ($type == 'json') {
			$this->getJsonData($entity);
		}

		$this->setEntity($entity);
		$data = $this->em->getRepository($this->entity)->findAll();
		return $data;
	}

	public function getWithSimpleCriteria(string $entity, array $criteria): ?array
	{
		$this->setEntity($entity);
		$data = $this->em->getRepository($this->entity)->findBy($criteria);
		return $data;
	}

	public function getWithSimpleCriteriaJson(string $entity, array $criteria) : ? array
	{
		$this->setEntity($entity);
		$data = (array) $this->em->getRepository($this->entity)->findBy($criteria);
		dump($data);

		die();	
		$serializer = SerializerBuilder::create()->build();
		$response = $serializer->serialize($data, 'json');
		return $data;
	}

	public function remove(string $id, string $entity = null): void
	{
		$this->setEntity($entity);
		
		$registry = $this->em->getRepository($this->entity)->find($id);
		
		$this->em->remove($registry);
		$this->commit('Erro na operação');
	}

	public function commit($errorMessage = null): void
	{
		try {
			$this->em->flush();
			$this->userMessage = 'Operação feita com sucesso';
		} catch (\Exception $e) {
			$this->typeMessage = 'danger';
			$this->userMessage = $errorMessage ?? $e->getMessage();
			$this->devMessage = $e->getMessage();
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