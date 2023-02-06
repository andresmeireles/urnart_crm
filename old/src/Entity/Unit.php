<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnitRepository")
 */
class Unit
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=2, unique=true)
     */
    private $initials;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $name;
    
    public function __construct()
    {
        $this->departament = new ArrayCollection();
    }

    public function __toArray(): array
    {
        return get_object_vars($this);
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getInitials(): ?string
    {
        return $this->initials;
    }

    public function setInitials(string $initials): self
    {
        $this->initials = $initials;

        return $this;
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
}
