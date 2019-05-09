<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ManualOrderReportRepository")
 */
class ManualOrderReport extends BaseEntity
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
    private $customerName;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $customerCity;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentType;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $freight;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $transporter;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $port;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ManualProductCart", mappedBy="manualOrderReport")
     */
    private $manualProductCarts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TravelTruckOrders", inversedBy="orderId")
     */
    private $travelTruckOrders;

    public function __construct()
    {
        parent::__construct();
        $this->manualProductCarts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): self
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getCustomerCity(): ?string
    {
        return $this->customerCity;
    }

    public function setCustomerCity(string $customerCity): self
    {
        $this->customerCity = $customerCity;

        return $this;
    }

    public function getPaymentType(): ?PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(?PaymentType $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getFreight(): ?float
    {
        return $this->freight;
    }

    public function setFreight(float $freight): self
    {
        $this->freight = $freight;

        return $this;
    }

    public function getDiscount(): ?float
    {
        return $this->discount;
    }

    public function setDiscount(?float $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getTransporter(): ?string
    {
        return $this->transporter;
    }

    public function setTransporter(?string $transporter): self
    {
        $this->transporter = $transporter;

        return $this;
    }

    public function getPort(): ?string
    {
        return $this->port;
    }

    public function setPort(?string $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getComments(): ?string
    {
        return $this->comments;
    }

    public function setComments(?string $comments): self
    {
        $this->comments = $comments;

        return $this;
    }

    /**
     * @return Collection|ManualProductCart[]
     */
    public function getManualProductCarts(): Collection
    {
        return $this->manualProductCarts;
    }

    public function addManualProductCart(ManualProductCart $manualProductCart): self
    {
        if (!$this->manualProductCarts->contains($manualProductCart)) {
            $this->manualProductCarts[] = $manualProductCart;
            $manualProductCart->setManualOrderReport($this);
        }

        return $this;
    }

    public function removeManualProductCart(ManualProductCart $manualProductCart): self
    {
        if ($this->manualProductCarts->contains($manualProductCart)) {
            $this->manualProductCarts->removeElement($manualProductCart);
            // set the owning side to null (unless already changed)
            if ($manualProductCart->getManualOrderReport() === $this) {
                $manualProductCart->setManualOrderReport(null);
            }
        }

        return $this;
    }

    public function getOrderFinalPrice(): float
    {
        $cartValue = 0;
        foreach ($this->getManualProductCarts() as $key) {
            $cartValue += ($key->getProductPrice() * $key->getProductAmount());
        }

        $finalValue = ($cartValue + $this->getFreight()) - $this->getDiscount();
        return $finalValue;
    }

    public function getTravelTruckOrders(): ?TravelTruckOrders
    {
        return $this->travelTruckOrders;
    }

    public function setTravelTruckOrders(?TravelTruckOrders $travelTruckOrders): self
    {
        $this->travelTruckOrders = $travelTruckOrders;

        return $this;
    }
}
