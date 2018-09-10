<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductsOfTheCartRepository")
 */
class ProductsOfTheCart
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
    private $product;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cart;

    /**
     * @ORM\Column(type="bigint")
     */
    private $amount;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $customPrice;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProduct(): ?string
    {
        return $this->product;
    }

    public function setProduct(string $product): self
    {
        $this->product = $product;

        return $this;
    }

    public function getCart(): ?string
    {
        return $this->cart;
    }

    public function setCart(string $cart): self
    {
        $this->cart = $cart;

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

    public function getCustomPrice(): ?int
    {
        return $this->customPrice;
    }

    public function setCustomPrice(?int $customPrice): self
    {
        $this->customPrice = $customPrice;

        return $this;
    }
}
