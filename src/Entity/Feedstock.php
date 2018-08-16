<?php 
declare (strict_types = 1);

namespace App\Entity;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

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
     * @var string
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

    public function getDescription() : ? string
    {
        return $this->description;
    }

    public function setDescription(? string $description) : self
    {
        $this->description = $description;

        return $this;
    }

    public function getVendors() : ? array
    {
        return $this->vendors;
    }

    /**
     * Return the first item of array
     *
     * @return string|null
     */
    public function getMainVendor() : ?string
    {
        $mainVendor = $this->vendors[0]; 
        return $mainVendor;
    }

    public function setVendors(? array $vendors) : self
    {
        $this->vendors = $vendors;

        return $this;
    }

    public function getperiodicity() : ? int
    {
        return $this->periodicity;
    }

    public function setperiodicity(int $periodicity) : self
    {
        $this->periodicity = $periodicity;

        return $this;
    }

    public function getFeedstockInventory() : ? FeedstockInventory
    {
        return $this->feedstockInventory;
    }

    public function setFeedstockInventory(? FeedstockInventory $feedstockInventory) : self
    {
        $this->feedstockInventory = $feedstockInventory;

        // set (or unset) the owning side of the relation if necessary
        $newFeedstock_id = $feedstockInventory === null ? null : $this;
        if ($newFeedstock_id !== $feedstockInventory->getFeedstockId()) {
            $feedstockInventory->setFeedstockId($newFeedstock_id);
        }

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
}
