<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentTypeRepository")
 */
class PaymentType
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
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $plot = false;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="customer")
     */
    private $orderNumber;

    public function __construct()
    {
        $this->orderNumber = new ArrayCollection();
    }

    public function __toString(): ?string
    {
        return $this->name;
    }

    public function __toArray()
    {
        return get_object_vars($this);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function setPlot(?bool $plot): self
    {
        $plot = (bool) $plot;

        $this->plot = $plot;

        return $this;
    }

    public function isPlot(): bool
    {
        return (bool) $this->plot;
    }

    public function addOrderNumber(Order $orderNumber): self
    {
        if (!$this->orderNumber->contains($orderNumber)) {
            $this->orderNumber[] = $orderNumber;
            $orderNumber->setPaymentType()($this);
        }

        return $this;
    }

    public function removeOrderNumber(Order $orderNumber): self
    {
        if ($this->orderNumber->contains($orderNumber)) {
            $this->orderNumber->removeElement($orderNumber);
            // set the owning side to null (unless already changed)
            if ($orderNumber->getPaysetPaymentType()() === $this) {
                $orderNumber->setPaymentType()(null);
            }
        }

        return $this;
    }
}
