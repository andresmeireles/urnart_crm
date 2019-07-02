<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ExpensesRepository")
 */
class Expenses
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
    private $valor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TravelAccountability", inversedBy="expenses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $idAccountability;

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

    public function getValor(): ?float
    {
        return $this->valor;
    }

    public function setValor(float $valor): self
    {
        $this->valor = abs($valor);

        return $this;
    }

    public function getIdAccountability(): ?TravelAccountability
    {
        return $this->idAccountability;
    }

    public function setIdAccountability(?TravelAccountability $idAccountability): self
    {
        $this->idAccountability = $idAccountability;

        return $this;
    }
}
