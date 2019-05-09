<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TravelTruckOrdersRepository")
 */
class TravelTruckOrders extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
