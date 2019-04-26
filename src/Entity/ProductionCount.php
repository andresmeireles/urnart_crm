<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Utils\Andresmei\StringConvertions;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductionCountRepository")
 */
class ProductionCount extends BaseEntity
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
    private $model;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $height;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $cleanedModel = trim($model);
        $cleanedModel = strtoupper($model);

        $this->model = $cleanedModel;

        return $this;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(string $height): self
    {
        $this->height = trim($height);

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(string $productionDate): self
    {
        $date = (new StringConvertions())->convertStringToDate($productionDate, '-', 'POR', '/');

        $this->date = new \DateTime($date, new \DateTimeZone('America/Belem'));

        return $this;
    }
}
