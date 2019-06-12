<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Utils\Exceptions\CustomException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TravelTruckOrdersRepository")
 */
class TravelTruckOrders extends BaseEntity
{
    /**
     * @var int
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $driverName;

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $departureDate;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     */
    private $kmout;

    /**
     * @var Collection|ArrayCollection
     * @ORM\OneToMany(targetEntity="App\Entity\ManualOrderReport", mappedBy="travelTruckOrders")
     */
    private $orderId;

    public function __construct()
    {
        $this->orderId = new ArrayCollection();
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDriverName(): ?string
    {
        return $this->driverName;
    }

    public function setDriverName(?string $driverName): self
    {
        $clearString = trim($driverName);
        if (strlen($clearString) === 0) {
            throw new CustomException(
                sprintf('Erro. Parametro %s não aceito como nome', $driverName)
            );
        }
        $this->driverName = $clearString;

        return $this;
    }
    
    public function getDepartureDate(): ?\DateTime
    {
        return $this->departureDate;
    }

    public function setDepartureDate(?\DateTime $departureDate): self
    {
        $this->departureDate = $departureDate; 

        return $this;
    }

    public function getKmout(): ?string
    {
        return $this->kmout;
    }

    public function setKmout(?string $kmout): self
    {
        $this->kmout = $kmout; 

        return $this;
    }
    /**
     * @return Collection|ManualOrderReport[]
     */
    public function getOrderId(): Collection
    {
        return $this->orderId;
    }

    public function addOrderId(ManualOrderReport $orderId): self
    {
        if (!$this->orderId->contains($orderId)) {
            $this->orderId[] = $orderId;
            $orderId->setTravelTruckOrders($this);
        }

        return $this;
    }

    public function removeOrderId(ManualOrderReport $orderId): self
    {
        if ($this->orderId->contains($orderId)) {
            $this->orderId->removeElement($orderId);
            // set the owning side to null (unless already changed)
            if ($orderId->getTravelTruckOrders() === $this) {
                $orderId->setTravelTruckOrders(null);
            }
        }

        return $this;
    }
}
