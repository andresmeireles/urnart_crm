<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductInventoryRepository")
 */
class ProductInventory
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
    private $maxStock;

    /**
     * @ORM\Column(type="float")
     */
    private $minStock;

    /**
     * @ORM\Column(type="float")
     */
    private $stock = 0;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Product", inversedBy="productInventory", cascade={"persist", "remove"})
     */
    private $product;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $reserved;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $nonReservedStock;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMaxStock(): ?int
    {
        return $this->maxStock;
    }

    public function setMaxStock(int $maxStock): self
    {
        $this->maxStock = $maxStock;

        return $this;
    }

    public function getMinStock(): ?float
    {
        return $this->minStock;
    }

    public function setMinStock(float $minStock): self
    {
        $this->minStock = $minStock;

        return $this;
    }

    public function getStock(): ?float
    {
        return $this->stock;
    }

    public function setStock(float $stock): self
    {
        $this->stock = $stock;

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

    public function getReserved(): ?int
    {
        return $this->reserved;
    }

    public function setReserved(int $reserved): self
    {
        $this->reserved = $reserved;

        return $this;
    }

    public function getNonReservedStock(): ?int
    {
        return $this->nonReservedStock;
    }

    public function setNonReservedStock(?int $nonReservedStock): self
    {
        $this->nonReservedStock = $nonReservedStock;

        return $this;
    }
}
