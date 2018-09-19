<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentTypeRepository")
 */
class PaymentType
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $plot = false;

    public function __toString()
    {
        return $this->name;
    }

    public function __toArray()
	{
		return get_object_vars($this);
	}

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function setPlot(?bool $plot): self
    {
        $plot = (bool) $plot;

        $this->plot = $plot;

        return $this;
    }

    public function isPlot(): bool
    {
        return (bool) $this->plot;
    }
}
