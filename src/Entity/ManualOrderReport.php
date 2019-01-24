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
     * @ORM\Column(type="string", nullable=true)
     */
    private $port;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comments;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ManualProductCart", mappedBy="ManualOrderReport")
     */
    private $manualProductCarts;

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
}
