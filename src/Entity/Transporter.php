<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TransporterRepository")
 */
final class Transporter
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $port;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="transporter")
     */
    private $orderNumber;

    public function __construct()
    {
        $this->orderNumber = new ArrayCollection();
    }

    public function __toArray(): ?array
    {
        return get_object_vars($this);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPort(): ?string
    {
        return $this->port;
    }

    public function setPort(string $port): self
    {
        $this->port = $port;

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

    /**
     * @return Collection|Order[]
     */
    public function getOrderNumber(): Collection
    {
        return $this->orderNumber;
    }

    public function addOrderNumber(Order $orderNumber): self
    {
        if (! $this->orderNumber->contains($orderNumber)) {
            $this->orderNumber[] = $orderNumber;
            $orderNumber->setTransporter($this);
        }

        return $this;
    }

    public function removeOrderNumber(Order $orderNumber): self
    {
        if ($this->orderNumber->contains($orderNumber)) {
            $this->orderNumber->removeElement($orderNumber);
            // set the owning side to null (unless already changed)
            if ($orderNumber->getTransporter() === $this) {
                $orderNumber->setTransporter(null);
            }
        }

        return $this;
    }
}
