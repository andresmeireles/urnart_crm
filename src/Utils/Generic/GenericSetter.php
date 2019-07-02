<?php declare(strict_types = 1);

namespace App\Utils\Generic;

trait GenericSetter
{
    public function setFields(array $parameters): self
    {
        $this->entity = new $this->entity();
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

        foreach ($elements as $value) {
            if ($value === null) {
                $counter++;
            }
        }

        if ($counter === count($elements)) {
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
