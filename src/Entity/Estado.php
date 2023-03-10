<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\EstadoRepository")
 */
class Estado
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer", name="codigoUf")
     */
    private $codigoUf;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $uf;

    /**
     * @ORM\Column(type="integer")
     */
    private $regiao;

    public function __construct()
    {
        $this->endereco = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCodigoUf(): ?int
    {
        return $this->codigoUf;
    }

    public function setCodigoUf(int $codigoUf): self
    {
        $this->codigoUf = abs($codigoUf);

        return $this;
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

    public function getUf(): ?string
    {
        return $this->uf;
    }

    public function setUf(string $uf): self
    {
        $this->uf = $uf;

        return $this;
    }

    public function getRegiao(): ?int
    {
        return $this->regiao;
    }

    public function setRegiao(int $regiao): self
    {
        $this->regiao = abs($regiao);

        return $this;
    }
}
