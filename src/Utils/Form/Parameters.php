<?php

namespace App\Utils\Form;

class Parameters
{
    private $parameters;

    private $nonClonedParameters;
    
    private $clonedParameters;

    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
        $this->nonClonedParameters = $parameters;

        foreach ($parameters as $key => $value) {
            if (is_array($value)) {
                $this->clonedParameters[$key] = $value;
                unset($this->nonClonedParameters[$key]);
            }
        }
    }

    public function getAllParameters(): array
    {
        return $this->parameters;
    }

    public function getNonClonedParameters(): array
    {
        return $this->nonClonedParameters;
    }

    public function getClonedParameters(): ?array
    {
        return $this->clonedParameters;
    }
}
