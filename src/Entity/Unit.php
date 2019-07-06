<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UnitRepository")
 */
final class Unit
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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Feedstock", mappedBy="unit")
     */
    private $departament;

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

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }

    /**
     * @return Collection|Feedstock[]
     */
    public function getDepartament(): Collection
    {
        return $this->departament;
    }

    public function addDepartament(Feedstock $departament): self
    {
        if (! $this->departament->contains($departament)) {
            $this->departament[] = $departament;
            $departament->setUnit($this);
        }

        return $this;
    }

    public function removeDepartament(Feedstock $departament): self
    {
        if ($this->departament->contains($departament)) {
            $this->departament->removeElement($departament);
            // set the owning side to null (unless already changed)
            if ($departament->getUnit() === $this) {
                $departament->setUnit(null);
            }
        }

        return $this;
    }
}
