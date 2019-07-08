<?php declare(strict_types = 1);

namespace App\Utils\Generic;

use App\Utils\Andresmei\FlashResponse;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use JMS\Serializer\SerializerBuilder;

/**
 * Generic Crud functions.
 * Tests OK
 */
final class Crud extends GenericContainer
{
    use GenericSetter;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var string
     */
    private $userMessage;

    /**
     * @var  string
     */
    private $typeMessage = 'success';

    /**
     * @var string
     */
    private $devMessage;

    /**
     * @param string $entity
     */
    public function setEntity(string $entity): void
    {
        $className = 'App\Entity\\' . ucwords($entity);
        $this->entity = $className;
    }

    /**
     * @param string $id
     * @param string $entity
     * @return FlashResponse
     * @throws \Exception
     */
    public function remove(string $id, string $entity): FlashResponse
    {
        $this->setEntity($entity);
        $registry = $this->entityManager->getRepository($this->entity)->find($id);
        try {
            $this->entityManager->remove($registry);
            $this->entityManager->flush();
        } catch (ForeignKeyConstraintViolationException $e) {
            throw new \Exception('Item não pode ser removido pois está sendo utilizado em algum pedido.');
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }

        return new FlashResponse(200, 'success', sprintf('Produto %d removido com sucesso!', $id));
    }

    /**
     * @param Collection $entries
     * @return bool
     * @throws \Exception
     */
    public function removeManyEntries(Collection $entries): bool
    {
        $entityManager = $this->entityManager;
        // $entries = $entityManager->getRepository($entity)->findBy(['id' => $id]);
        // $entries->forAll(function ($k, $v) {
        //     dump($v);
        // });
        try {
            $entries->forAll(static function ($value) use ($entityManager) {
                $entityManager->remove($value);
            });
        } catch (\Exception $error) {
            throw new \Exception(
                sprintf(
                    '%s, no arquivo %s e linha %s',
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

    public function commit(): void
    {
        try {
            $this->entityManager->flush();
            $this->userMessage = 'Operação feita com sucesso';
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage());
        }
    }

    /**
     * @return string
     */
    public function getTypeMessage(): string
    {
        return $this->typeMessage;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->userMessage;
    }

    /**
     * @return string
     */
    public function getDevMessage(): string
    {
        return $this->devMessage;
    }

    /**
     * @param string $entity
     * @param string|null $type
     * @return array|null
     */
    public function get(string $entity, ?string $type = null): ?array
    {
        if ($type === 'json') {
            $this->getJsonData($entity);
        }
        $this->setEntity($entity);

        return $this->entityManager->getRepository($this->entity)->findAll();
    }

    /**
     * @param string $entity
     * @return string
     */
    public function getJsonData(string $entity): string
    {
        $this->setEntity($entity);
        $serializer = SerializerBuilder::create()->build();
        $data = $this->entityManager->getRepository($this->entity)->findAll();

        return $serializer->serialize($data, 'json');
    }

    /**
     * @param string $entity
     * @param array $criteria
     * @return array|null
     */
    public function getWithSimpleCriteria(string $entity, array $criteria): ?array
    {
        $this->setEntity($entity);

        return $this->entityManager->getRepository($this->entity)->findBy($criteria);
    }

    /**
     * @param string $entity
     * @param array $criteria
     * @return string|null
     */
    public function getWithSimpleCriteriaJson(string $entity, array $criteria): ?string
    {
        $this->setEntity($entity);
        $serializer = SerializerBuilder::create()->build();
        $data = $this->entityManager->getRepository($this->entity)->findBy($criteria);

        return $serializer->serialize($data, 'json');
    }

    /**
     * @param string $entity
     * @param int $id
     * @return object|null
     */
    public function getRegisterById(string $entity, int $id): ?object
    {
        $this->setEntity($entity);
        $repository = $this->entityManager->getRepository($this->entity);
        return $repository->find($id);
    }
}
