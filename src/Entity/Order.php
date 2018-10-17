<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use PhpParser\Node\Expr\Cast\Bool_;

/**
 * @ORM\Entity(repositoryClass="App\Repository\OrderRepository")
 * @ORM\Table(name="`order`")
 */
class Order extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $customPort;

    /**
     * @ORM\Column(type="float")
     */
    private $totalPrice;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $discount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $freight;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $installment;

    /**
     * @ORM\Column(type="string", length=1500, nullable=true)
     */
    private $comments;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PessoaJuridica", inversedBy="orderNumber")
     */
    private $customer;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\ProductCart", mappedBy="orderNumber")
     */
    private $productCarts;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Transporter", inversedBy="orderNumber")
     */
    private $transporter;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PaymentType", inversedBy="orderNumber")
     */
    private $paymentType;

    /**
     * @ORM\Column(type="smallint")
     * 
     * 0 - open
     * 1 - reserved
     * 2 - closed
     */
    private $reserved = false;

    public function __construct()
    {
        $this->customer = new ArrayCollection();
        $this->productCarts = new ArrayCollection();
        parent::__construct();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomPort(): ?string
    {
        return $this->customPort;
    }

    public function setCustomPort(?string $customport): self
    {
        $this->customPort = $customport;

        return $this;
    }

    public function getTotalPrice(): ?float
    {
        return $this->totalPrice;
    }

    public function setTotalPrice(float $totalPrice): self
    {
        $this->totalPrice = $totalPrice;

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

    public function getFreight(): ?float
    {
        return $this->freight;
    }

    public function setFreight(?float $freight): self
    {
        $this->freight = $freight;

        return $this;
    }

    public function getInstallment(): ?int
    {
        return $this->installment;
    }

    public function setInstallment(?int $installment): self
    {
        $this->installment = $installment;

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

    public function getAllCustomerData() : ?PessoaJuridica
    {
        return $this->customer;
    }

    public function getCustomerId(): ?string
    {
        return $this->customer->getId();
    }

    public function getCustomer(): ?string
    {
        return $this->customer->getRazaoSocial();
    }

    public function setCustomer(?PessoaJuridica $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection|ProductCart[]
     */
    public function getProductCarts(): Collection
    {
        return $this->productCarts;
    }

    public function addProductCart(ProductCart $productCart): self
    {
        if (!$this->productCarts->contains($productCart)) {
            $this->productCarts[] = $productCart;
            $productCart->setOrderNumber($this);
        }

        return $this;
    }

    public function removeProductCart(ProductCart $productCart): self
    {
        if ($this->productCarts->contains($productCart)) {
            $this->productCarts->removeElement($productCart);
            // set the owning side to null (unless already changed)
            if ($productCart->getOrderNumber() === $this) {
                $productCart->setOrderNumber(null);
            }
        }

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

    public function getPaymentType(): ?PaymentType
    {
        return $this->paymentType;
    }

    public function setPaymentType(?PaymentType $paymentType): self
    {
        $this->paymentType = $paymentType;

        return $this;
    }

    public function getReserved(): int
    {
        return $this->reserved;
    }

    public function reserve(): self
    {
        if ($this->reserved == 1 && $this->reserved == 2) {
            return $this;
        }

        $this->reserved = 1;
        return $this;
    }

    public function reOpen(): self
    {
        if ($this->reserved == 0 && $this->reserved == 2) {
            return $this;
        }

        $this->reserved = 0;

        return $this;
    }

    public function close(): self
    {
        if ($this->reserved == 2) {
            return $this;
        }

        $this->reserved = 2;

        return $this;
    }

    public function isReserved(): bool
    {
        if ($this->reserved == 1) {
            return true;
        }
        return false;
    }

    public function isOpen(): bool 
    {
        if ($this->reserved == 0) {
            return true;
        } 
        return false;
    }

    public function isClosed(): bool
    {
        if ($this->reserved === 2) {
            return true;
        }
        return false;
    }
}
