<?php

namespace App\Entity;

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
     * @ORM\OneToOne(targetEntity="App\Entity\Order", mappedBy="cart", cascade={"persist", "remove"})
     */
    private $orderNumber;

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
}
