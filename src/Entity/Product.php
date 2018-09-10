<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductRepository")
 */
class Product extends BaseEntity
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
     * @ORM\Column(type="string", length=4, nullable=true)
     */
    private $series;

    /**
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @ORM\Column(type="array")
     */
    private $color;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ProductInventory", mappedBy="product", cascade={"persist", "remove"})
     */
    private $productInventory;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ProductCart", inversedBy="product")
     */
    private $productCart;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\ProductCart", mappedBy="product")
     */
    private $productCarts;

    public function __construct()
    {
        parent::__construct();
        $this->productCarts = new ArrayCollection();
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

    public function getSeries(): ?string
    {
        return $this->series;
    }

    public function setSeries(?string $series): self
    {
        $this->series = $series;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getColor(): ?array
    {
        return $this->color;
    }

    public function setColor(array $color): self
    {
        $this->color = $color;

        return $this;
    }

    public function getProductInventory(): ?ProductInventory
    {
        return $this->productInventory;
    }

    public function setProductInventory(?ProductInventory $productInventory): self
    {
        $this->productInventory = $productInventory;

        // set (or unset) the owning side of the relation if necessary
        $newProduct = $productInventory === null ? null : $this;
        if ($newProduct !== $productInventory->getProduct()) {
            $productInventory->setProduct($newProduct);
        }

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->productInventory->getStock();
    }

    public function getMaxStock(): ?string
    {
        return $this->productInventory->getMaxStock();
    }

    public function getMinStock(): ?string
    {
        return $this->productInventory->getMinStock();
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
            $productCart->addProduct($this);
        }

        return $this;
    }

    public function removeProductCart(ProductCart $productCart): self
    {
        if ($this->productCarts->contains($productCart)) {
            $this->productCarts->removeElement($productCart);
            $productCart->removeProduct($this);
        }

        return $this;
    }
}
