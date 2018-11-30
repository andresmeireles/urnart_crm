<?php
namespace App\Utils\Generic;

use App\Utils\Generic\GenericContainer;
use App\Utils\Generic\GenericSetter;
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

    private $typeMessage = 'success';

    private $devMessage;

    use GenericSetter;

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
        } catch (\PDOException $e) {
            throw new \PDOException();
        }
    }

    public function getTypeMessage(): string
    {
        return $this->typeMessage;
    }

    public function getMessage() : string
    {
        return $this->userMessage;
    }

    public function getDevMessage() : string
    {
        return $this->devMessage;
    }

    public function get(string $entity, ? string $type = null) : ? array
    {
        if ($type == 'json') {
            $this->getJsonData($entity);
        }

        $this->setEntity($entity);
        $data = $this->em->getRepository($this->entity)->findAll();
        return $data;
    }

    public function getJsonData(string $entity)
    {
        $this->setEntity($entity);
        $serializer = SerializerBuilder::create()->build();

        $data = $this->em->getRepository($this->entity)->findAll();
        $jsonResponse = $serializer->serialize($data, 'json');

        return $jsonResponse;
    }

    public function getWithSimpleCriteria(string $entity, array $criteria): ?array
    {
        $this->setEntity($entity);
        $data = $this->em->getRepository($this->entity)->findBy($criteria);
        return $data;
    }

    public function getWithSimpleCriteriaJson(string $entity, array $criteria) : ?string
    {
        $this->setEntity($entity);
        $serializer = SerializerBuilder::create()->build();
        $data = $this->em->getRepository($this->entity)->findBy($criteria);
        $response = $serializer->serialize($data, 'json');
        return $response;
    }

    public function getRegisterById(string $entity, int $id): ?object
    {
        $this->setEntity($entity);
        $repository = $this->em->getRepository($this->entity);
        $result = $repository->find($id);
        return $result;
    }
}
