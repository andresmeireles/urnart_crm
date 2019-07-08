<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ModelNameRepository")
 */
final class ModelName extends BaseEntity
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
     * @ORM\Column(type="float", nullable=true)
     */
    private $suggestedPrice;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $colors = [];

    /**
     * @ORM\Column(type="string")
     */
    private $height;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $specificity;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $sanitizedName = str_replace(' ', '', $name);
        $allowedConvesionableNames = [
            'ECOCV' => 'ECO CV',
            'ECOCVSV' => 'ECO CV SV',
            'ECOSVSV' => 'ECO SV SV',
            'ECOSV' => 'ECO SV',
        ];
        $sanitizedName = strtoupper($sanitizedName);
        $finalModelName = array_key_exists($sanitizedName, $allowedConvesionableNames) ?
            $allowedConvesionableNames[$sanitizedName] :
            $sanitizedName;
        $this->name = $finalModelName;

        return $this;
    }

    public function getSuggestedPrice(): ?float
    {
        return $this->suggestedPrice;
    }

    public function setSuggestedPrice(?float $suggestedPrice): self
    {
        $this->suggestedPrice = $suggestedPrice === null ? null : abs($suggestedPrice);

        return $this;
    }

    public function getColors(): ?array
    {
        return $this->colors;
    }

    public function setColors(?array $colors): self
    {
        $this->colors = $colors;

        return $this;
    }

    public function getHeight(): string
    {
        return $this->height;
    }

    public function setHeight(string $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getSpecificity(): ?string
    {
        return $this->specificity;
    }

    public function setSpecificity(?string $specificity): self
    {
        $this->specificity = $specificity === null || $specificity === '' ? null : strtoupper($specificity);

        return $this;
    }
}
