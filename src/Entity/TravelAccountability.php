<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TravelAccountabilityRepository")
 */
class TravelAccountability extends BaseEntity
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
    private $driverName;

    /**
     * @ORM\Column(type="datetime")
     */
    private $departureDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $arrivalDate;

    /**
     * @ORM\Column(type="float")
     */
    private $departureKm;

    /**
     * @ORM\Column(type="float")
     */
    private $arrivalKm;

    /**
     * @ORM\Column(type="float")
     */
    private $cash;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Expenses", mappedBy="idAccountability")
     */
    private $expenses;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TravelEntry", mappedBy="idTravelAccountability")
     */
    private $travelEntries;

    public function __construct()
    {
        $this->expenses = new ArrayCollection();
        $this->travelEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDriverName(): ?string
    {
        return $this->driverName;
    }

    public function setDriverName(string $driverName): self
    {
        $this->driverName = $driverName;

        return $this;
    }

    public function getDepartureDate(): ?\DateTimeInterface
    {
        return $this->departureDate;
    }

    public function setDepartureDate(\DateTimeInterface $departureDate): self
    {
        $this->departureDate = $departureDate;

        return $this;
    }

    public function getArrivalDate(): ?\DateTimeInterface
    {
        return $this->arrivalDate;
    }

    public function setArrivalDate(\DateTimeInterface $arrivalDate): self
    {
        $this->arrivalDate = $arrivalDate;

        return $this;
    }

    public function getDepartureKm(): ?float
    {
        return $this->departureKm;
    }

    public function setDepartureKm(float $departureKm): self
    {
        $this->departureKm = $departureKm;

        return $this;
    }

    public function getArrivalKm(): ?float
    {
        return $this->arrivalKm;
    }

    public function setArrivalKm(float $arrivalKm): self
    {
        $this->arrivalKm = $arrivalKm;

        return $this;
    }

    public function getCash(): ?float
    {
        return $this->cash;
    }

    public function setCash(float $cash): self
    {
        $this->cash = $cash;

        return $this;
    }

    /**
     * @return Collection|Expenses[]
     */
    public function getExpenses(): Collection
    {
        return $this->expenses;
    }

    public function addExpense(Expenses $expense): self
    {
        if (!$this->expenses->contains($expense)) {
            $this->expenses[] = $expense;
            $expense->setIdAccountability($this);
        }

        return $this;
    }

    public function removeExpense(Expenses $expense): self
    {
        if ($this->expenses->contains($expense)) {
            $this->expenses->removeElement($expense);
            // set the owning side to null (unless already changed)
            if ($expense->getIdAccountability() === $this) {
                $expense->setIdAccountability(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|TravelEntry[]
     */
    public function getTravelEntries(): Collection
    {
        return $this->travelEntries;
    }

    public function addTravelEntry(TravelEntry $travelEntry): self
    {
        if (!$this->travelEntries->contains($travelEntry)) {
            $this->travelEntries[] = $travelEntry;
            $travelEntry->setIdTravelAccountability($this);
        }

        return $this;
    }

    public function removeTravelEntry(TravelEntry $travelEntry): self
    {
        if ($this->travelEntries->contains($travelEntry)) {
            $this->travelEntries->removeElement($travelEntry);
            // set the owning side to null (unless already changed)
            if ($travelEntry->getIdTravelAccountability() === $this) {
                $travelEntry->setIdTravelAccountability(null);
            }
        }

        return $this;
    }
}
