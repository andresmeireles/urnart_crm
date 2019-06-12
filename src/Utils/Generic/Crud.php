<?php declare(strict_types=1);

namespace App\Utils\Generic;

use App\Utils\Andresmei\FlashResponse;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use JMS\Serializer\SerializerBuilder;
use Doctrine\Common\Collections\Collection;

/**
 * Generic Crud functions.
 * 
 * Tests OK
 */
class Crud extends GenericContainer
{
    /**
     * Nome da entidade utilizada
     *
     * @var string
     */
    private $entity;

    /**
     * Mensagem de retorno da ação
     *
     * @var string
     */
    private $userMessage;

    /**
     * Tipo de retorno
     *
     * @var  string
     */
    private $typeMessage = 'success';
    
    private $devMessage;

    use GenericSetter;

    public function setEntity(string $entity): void
    {
        $className = 'App\Entity\\'.ucwords($entity);
        $this->entity = $className;
    }

    public function remove(string $id, string $entity): FlashResponse
    {
        $this->setEntity($entity);
        $registry = $this->em->getRepository($this->entity)->find($id);
        try {
            $this->em->remove($registry);
            $this->em->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            throw new \Exception('Item não pode ser removido pois está sendo utilizado em algum pedido.');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        
        return new FlashResponse(200, 'success', sprintf('Produto %d removido com sucesso!', $id));
    }

    /**
     * Remove grupo de entradas em uma entidade
     *
     * @param string $id
     * @param object $entity
     * @return boolean
     * 
     * @throws Exception
     */
    public function removeManyEntries(Collection $entries): bool
    {
        $entityManager = $this->em;
        // $entries = $entityManager->getRepository($entity)->findBy(['id' => $id]);
        // $entries->forAll(function ($k, $v) {
        //     dump($v);
        // });
        try {
            $entries->forAll(function ($k, $value) use ($entityManager) {
                $entityManager->remove($value);
            });
        } catch (\Exception $error) {
            throw new \Exception(
                sprintf(
                    "%s, no arquivo %s e linha %s",
                    $error->getMessage(),
                    $error->getFile(),
                    $error->getLine()
                ),
                $error->getCode()
            );
        }
        $entityManager->flush();

        return true;
    }

    public function commit($errorMessage = null): void
    {
        try {
            $this->em->flush();
            $this->userMessage = 'Operação feita com sucesso';
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
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
        if ($type === 'json') {
            $this->getJsonData($entity);
        }

        $this->setEntity($entity);

        return $this->em->getRepository($this->entity)->findAll();
    }

    public function getJsonData(string $entity)
    {
        $this->setEntity($entity);
        $serializer = SerializerBuilder::create()->build();
        $data = $this->em->getRepository($this->entity)->findAll();
        
        return $serializer->serialize($data, 'json');
    }

    public function getWithSimpleCriteria(string $entity, array $criteria): ?array
    {
        $this->setEntity($entity);

        return $this->em->getRepository($this->entity)->findBy($criteria);
    }

    public function getWithSimpleCriteriaJson(string $entity, array $criteria) : ?string
    {
        $this->setEntity($entity);
        $serializer = SerializerBuilder::create()->build();
        $data = $this->em->getRepository($this->entity)->findBy($criteria);

        return $serializer->serialize($data, 'json');
    }

    public function getRegisterById(string $entity, int $id): ?object
    {
        $this->setEntity($entity);
        $repository = $this->em->getRepository($this->entity);
        return $repository->find($id);
    }
}
