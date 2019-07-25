<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductCartRepository")
 */
class ProductCart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="bigint")
     */
    private $amount;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $customPrice;

    //@ORM\OneToOne(targetEntity="App\Entity\Order", cascade={"persist", "remove"})

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Order", inversedBy="productCarts")
     */
    private $orderNumber;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product", inversedBy="productCarts")
     */
    private $product;

    public function __construct()
    {
        $this->orderNumber = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCustomPrice(): ?float
    {
        return $this->customPrice;
    }

    public function setCustomPrice(float $customPrice): self
    {
        $this->customPrice = $customPrice;

        return $this;
    }

    public function getOrderNumber(): ?Order
    {
        return $this->orderNumber;
    }

    public function setOrderNumber(?Order $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}
