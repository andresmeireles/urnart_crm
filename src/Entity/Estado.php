<?php

namespace App\Entity;

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
     * @ORM\OneToMany(targetEntity="App\Entity\Municipio", mappedBy="uf")
     */
    private $ini_uf;

    /**
     * @ORM\Column(type="integer")
     */
    private $regiao;

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
        $this->codigoUf = $codigoUf;

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
        $this->regiao = $regiao;

        return $this;
    }
}
