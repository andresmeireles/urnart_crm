<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TravelEntryRepository")
 */
class TravelEntry
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
    private $nome;

    /**
     * @ORM\Column(type="float")
     */
    private $freight;

    /**
     * @ORM\Column(type="float")
     */
    private $orderValue;

    /**
     * @ORM\Column(type="float")
     */
    private $checkValue;

    /**
     * @ORM\Column(type="float")
     */
    private $byCountValue;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TravelAccountability", inversedBy="travelEntries")
     */
    private $orderReference;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNome(): ?string
    {
        return $this->nome;
    }

    public function setNome(string $nome): self
    {
        $this->nome = $nome;

        return $this;
    }

    public function getFreight(): ?float
    {
        return $this->freight;
    }

    public function setFreight(float $freight): self
    {
        $this->freight = $freight;

        return $this;
    }

    public function getOrderValue(): ?float
    {
        return $this->orderValue;
    }

    public function setOrderValue(float $orderValue): self
    {
        $this->orderValue = $orderValue;

        return $this;
    }

    public function getCheckValue(): ?float
    {
        return $this->checkValue;
    }

    public function setCheckValue(float $checkValue): self
    {
        $this->checkValue = $checkValue;

        return $this;
    }

    public function getByCountValue(): ?float
    {
        return $this->byCountValue;
    }

    public function setByCountValue(float $byCountValue): self
    {
        $this->byCountValue = $byCountValue;

        return $this;
    }

    public function getOrderReference(): ?TravelAccountability
    {
        return $this->orderReference;
    }

    public function setOrderReference(?TravelAccountability $orderReference): self
    {
        $this->orderReference = $orderReference;

        return $this;
    }
}
