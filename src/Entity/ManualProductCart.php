<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ManualProductCartRepository")
 */
final class ManualProductCart
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
     * @ORM\Column(type="float")
     */
    private $productPrice;

    /**
     * @ORM\Column(type="bigint")
     */
    private $productAmount;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\ManualOrderReport", inversedBy="manualProductCarts")
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

    public function getProductPrice(): ?float
    {
        return $this->productPrice;
    }

    public function setProductPrice(float $productPrice): self
    {
        $this->productPrice = abs($productPrice);

        return $this;
    }

    public function getProductAmount(): ?int
    {
        return (int) $this->productAmount;
    }

    public function setProductAmount(int $productAmount): self
    {
        $this->productAmount = abs($productAmount);

        return $this;
    }

    public function getManualOrderReport(): ?ManualOrderReport
    {
        return $this->manualOrderReport;
    }

    public function setManualOrderReport(?ManualOrderReport $manualOrderReport): self
    {
        $this->manualOrderReport = $manualOrderReport;

        return $this;
    }
}
