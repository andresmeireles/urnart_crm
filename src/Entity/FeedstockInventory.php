<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FeedstockInventoryRepository")
 */
class FeedstockInventory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Feedstock", inversedBy="feedstockInventory", cascade={"persist", "remove"})
     */
    private $feedstock_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $stock = '0';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $maxStock = '0';

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $minStock = '0';

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFeedstockId(): ?Feedstock
    {
        return $this->feedstock_id;
    }

    public function setFeedstockId(?Feedstock $feedstock_id): self
    {
        $this->feedstock_id = $feedstock_id;

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->stock;
    }

    public function setStock(string $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getMaxStock(): ?string
    {
        return $this->maxStock;
    }

    public function setMaxStock(string $maxStock): self
    {
        $this->maxStock = $maxStock;

        return $this;
    }

    public function getMinStock(): ?string
    {
        return $this->minStock;
    }

    public function setMinStock(string $minStock): self
    {
        $this->minStock = $minStock;

        return $this;
    }
}
