<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeedstockRepository")
 */
class Feedstock extends BaseEntity
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
    private $nome;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     *
     * @var string|null
     */
    private $description;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $vendors;

    /**
     * @ORM\Column(type="integer")
     */
    private $periodicity;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\FeedstockInventory", mappedBy="feedstock_id", cascade={"persist", "remove"})
     */
    private $feedstockInventory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Unit", inversedBy="departament")
     */
    private $unit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Departament", inversedBy="feedstocks")
     */
    private $departament;

    public function getId() : ? int
    {
        return $this->id;
    }

    public function getNome() : ? string
    {
        return $this->nome;
    }

    public function setNome(string $nome) : self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description) : self
    {
        $this->description = $description;

        return $this;
    }

    public function getVendors() : ? array
    {
        return $this->vendors;
    }

    public function setVendors(? array $vendors) : self
    {
        $this->vendors = $vendors;

        return $this;
    }
    
    /**
     * Return the first item of array
     *
     * @return string|null
     */
    public function getMainVendor() : ?string
    {
        return $this->vendors[0];
    }

    /**
     * Return other vendors in an array
     *
     * @return array|null
     */
    public function getOtherVendors(): ?array
    {
        if (count($this->vendors) > 1) {
            $otherVendors = $this->getVendors();
            array_shift($otherVendors);
            return $otherVendors;
        }
        return [];
    }

    public function getPeriodicity() : ? int
    {
        return $this->periodicity;
    }

    public function setPeriodicity(int $periodicity) : self
    {
        $this->periodicity = $periodicity;

        return $this;
    }

    public function getFeedstockInventory() : ? FeedstockInventory
    {
        return $this->feedstockInventory;
    }

    public function setFeedstockInventory(?FeedstockInventory $feedstockInventory) : self
    {
        $this->feedstockInventory = $feedstockInventory;

        // set (or unset) the owning side of the relation if necessary
        $newFeedstock_id = $feedstockInventory === null ? null : $this;
        if ($newFeedstock_id !== $feedstockInventory->getFeedstockId()) {
            $feedstockInventory->setFeedstockId($newFeedstock_id);
        }

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->feedstockInventory->getStock();
    }

    public function getMaxStock(): ?string
    {
        return $this->feedstockInventory->getMaxStock();
    }

    public function setMaxStock(?int $stock): self
    {
        $this->feedstockInventory->setMaxStock($stock);
        return $this;
    }

    public function getMinStock() : ?string
    {
        return $this->feedstockInventory->getMinStock();
    }

    public function setMinStock(?int $stock): self
    {
        $this->feedstockInventory->setMinStock($stock);

        return $this;
    }

    public function getUnit() : ? Unit
    {
        return $this->unit;
    }

    public function setUnit(? Unit $unit) : self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getDepartament() : ? Departament
    {
        return $this->departament;
    }

    public function setDepartament(? Departament $departament) : self
    {
        $this->departament = $departament;

        return $this;
    }

    public function getLastUpdate(): ?string
    {
        return $this->lastUpdate->format('d/m/Y');
    }
}
