<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ManualOrderReportRepository")
 */
class ManualOrderReport
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
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCart")
     * @ORM\JoinColumn(nullable=false)
     */
    private $productCart;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $paymentType;

    /**
     * @ORM\Column(type="bigint")
     */
    private $freight;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $discount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporter")
     */
    private $transporter;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ManualProductCart", mappedBy="manualOrderReport", cascade={"persist", "remove"})
     */
    private $manualProductCart;

    public function __construct()
    {
        $this->cart = new ArrayCollection();
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

    public function getProductCart(): ?ProductCart
    {
        return $this->productCart;
    }

    public function setProductCart(?ProductCart $productCart): self
    {
        $this->productCart = $productCart;

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

    public function getFreight(): ?int
    {
        return $this->freight;
    }

    public function setFreight(int $freight): self
    {
        $this->freight = $freight;

        return $this;
    }

    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    public function setDiscount(?int $discount): self
    {
        $this->discount = $discount;

        return $this;
    }

    public function getTransporter(): ?Transporter
    {
        return $this->transporter;
    }

    public function setTransporter(?Transporter $transporter): self
    {
        $this->transporter = $transporter;

        return $this;
    }

    public function getManualProductCart(): ?ManualProductCart
    {
        return $this->manualProductCart;
    }

    public function setManualProductCart(ManualProductCart $manualProductCart): self
    {
        $this->manualProductCart = $manualProductCart;

        // set the owning side of the relation if necessary
        if ($this !== $manualProductCart->getManualOrderReport()) {
            $manualProductCart->setManualOrderReport($this);
        }

        return $this;
    }
}
