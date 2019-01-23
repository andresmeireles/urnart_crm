<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ManualProductCartRepository")
 */
class ManualProductCart
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
    private $productName;

    /**
     * @ORM\Column(type="bigint")
     */
    private $productPrice;

    /**
     * @ORM\Column(type="bigint")
     */
    private $productAmount;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\ManualOrderReport", inversedBy="manualProductCart", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $manualOrderReport;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProductName(): ?string
    {
        return $this->productName;
    }

    public function setProductName(string $productName): self
    {
        $this->productName = $productName;

        return $this;
    }

    public function getProductPrice(): ?int
    {
        return $this->productPrice;
    }

    public function setProductPrice(int $productPrice): self
    {
        $this->productPrice = $productPrice;

        return $this;
    }

    public function getProductAmount(): ?int
    {
        return $this->productAmount;
    }

    public function setProductAmount(int $productAmount): self
    {
        $this->productAmount = $productAmount;

        return $this;
    }

    public function getManualOrderReport(): ?ManualOrderReport
    {
        return $this->manualOrderReport;
    }

    public function setManualOrderReport(ManualOrderReport $manualOrderReport): self
    {
        $this->manualOrderReport = $manualOrderReport;

        return $this;
    }
}
