<?php declare(strict_types = 1);

namespace App\Utils\Generic;

use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

trait GenericSetter
{
    /**
     * @param array $parameters
     * @return GenericSetter
     */
    public function setFields(array $parameters): self
    {
        $this->entity = new $this->entity();
        foreach ($parameters as $field => $value) {
            $setterFunction = 'set' . ucfirst($field);
            $this->entity->{$setterFunction}($value);
        }

        return $this;
    }

    /**
     * @throws \Exception
     */
    public function save(): void
    {
        $elements = $this->entity->__toArray();
        $counter = 0;

        foreach ($elements as $value) {
            if ($value === null) {
                $counter++;
            }
        }

        if ($counter === count($elements)) {
            throw new \Exception('Não é possível persistir elemento completamente vázio');
        }

        $this->entityManager->persist($this->entity);
        $this->entityManager->flush();
        $this->userMessage = 'Item cadastrado com sucesso';
    }

    /**
     * @param string $entity
     * @param array $parameters
     * @throws \Exception
     */
    public function set(string $entity, array $parameters): void
    {
        $this->setEntity($entity);
        $this->setFields($parameters);
        try {
            $this->save();
        } catch (UniqueConstraintViolationException $err) {
            $this->userMessage = 'Item já cadastrado';
            $this->devMessage = $err->getMessage();
            $this->typeMessage = 'danger';
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
}
