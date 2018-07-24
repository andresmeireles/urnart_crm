<?php 
namespace App\Utils\Generic;

use App\Utils\Generic\GenericContainer;

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

	private $typeMessage = 'success';

	public function setEntity(string $entity): void
	{
		$className = 'App\Entity\\'.ucwords($entity);
		$this->entity = $className;
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