<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\OneToOne(targetEntity="App\Entity\Order")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    private $orderNumber;

    /**
     * @ORM\Column(type="bigint")
     */
    private $amount;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Product", inversedBy="productCarts")
     */
    private $product;

    /**
     * @ORM\Column(targetEntity="App\Entity\Product", inversedBy="productCarts")
     */
    private $customPrice;

    public function __construct()
    {
        $this->product = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getorderNumber(): ?Order
    {
        return $this->orderNumber;
    }

    public function setorderNumber(?Order $orderNumber): self
    {
        $this->orderNumber = $orderNumber;

        // set (or unset) the owning side of the relation if necessary
        $newCart = $orderNumber === null ? null : $this;
        if ($newCart !== $orderNumber->getCart()) {
            $orderNumber->setCart($newCart);
        }

        return $this;
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

    /**
     * @return Collection|Product[]
     */
    public function getProduct(): Collection
    {
        return $this->product;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->product->contains($product)) {
            $this->product[] = $product;
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->product->contains($product)) {
            $this->product->removeElement($product);
        }

        return $this;
    }
}
