<?php

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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Feedstock", mappedBy="departament")
     */
    private $feedstocks;

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
    public function getFeedstocks(): Collection
    {
        return $this->feedstocks;
    }

    public function addFeedstock(Feedstock $feedstock): self
    {
        if (!$this->feedstocks->contains($feedstock)) {
            $this->feedstocks[] = $feedstock;
            $feedstock->setDepartament($this);
        }

        return $this;
    }

    public function removeFeedstock(Feedstock $feedstock): self
    {
        if ($this->feedstocks->contains($feedstock)) {
            $this->feedstocks->removeElement($feedstock);
            // set the owning side to null (unless already changed)
            if ($feedstock->getDepartament() === $this) {
                $feedstock->setDepartament(null);
            }
        }

        return $this;
    }
}
