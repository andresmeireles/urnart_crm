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
}
