<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DepartamentRepository")
 */
class Departament
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true, length=255)
     */
    private $name;

    public function __construct()
    {
        $this->feedstocks = new ArrayCollection();
    }

    public function __toArray()
    {
        return get_object_vars($this);
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = mb_strtolower($name);

        return $this;
    }

    public function getDepartamento(): ?string
    {
        return $this->name;
    }

    public function setDepartamento(string $name): self
    {
        $this->name = mb_strtolower($name);

        return $this;
    }
}
