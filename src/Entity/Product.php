<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product
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
    private $nome;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $vendors;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxStorage;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $minStorage;

    /**
     * @ORM\Column(type="integer")
     */
    private $periocidy;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Unit", mappedBy="product")
     */
    private $unit;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\departament", mappedBy="product")
     */
    private $departament;

    public function __construct()
    {
        $this->unit = new ArrayCollection();
        $this->departament = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getVendors(): ?array
    {
        return $this->vendors;
    }

    public function setVendors(?array $vendors): self
    {
        $this->vendors = $vendors;

        return $this;
    }

    public function getMaxStorage(): ?int
    {
        return $this->maxStorage;
    }

    public function setMaxStorage(?int $maxStorage): self
    {
        $this->maxStorage = $maxStorage;

        return $this;
    }

    public function getMinStorage(): ?int
    {
        return $this->minStorage;
    }

    public function setMinStorage(?int $minStorage): self
    {
        $this->minStorage = $minStorage;

        return $this;
    }

    public function getPeriocidy(): ?int
    {
        return $this->periocidy;
    }

    public function setPeriocidy(int $periocidy): self
    {
        $this->periocidy = $periocidy;

        return $this;
    }

    /**
     * @return Collection|Unit[]
     */
    public function getUnit(): Collection
    {
        return $this->unit;
    }

    public function addUnit(Unit $unit): self
    {
        if (!$this->unit->contains($unit)) {
            $this->unit[] = $unit;
            $unit->setProduct($this);
        }

        return $this;
    }

    public function removeUnit(Unit $unit): self
    {
        if ($this->unit->contains($unit)) {
            $this->unit->removeElement($unit);
            // set the owning side to null (unless already changed)
            if ($unit->getProduct() === $this) {
                $unit->setProduct(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|departament[]
     */
    public function getDepartament(): Collection
    {
        return $this->departament;
    }

    public function addDepartament(departament $departament): self
    {
        if (!$this->departament->contains($departament)) {
            $this->departament[] = $departament;
            $departament->setProduct($this);
        }

        return $this;
    }

    public function removeDepartament(departament $departament): self
    {
        if ($this->departament->contains($departament)) {
            $this->departament->removeElement($departament);
            // set the owning side to null (unless already changed)
            if ($departament->getProduct() === $this) {
                $departament->setProduct(null);
            }
        }

        return $this;
    }
}
