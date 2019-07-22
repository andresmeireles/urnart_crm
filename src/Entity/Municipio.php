<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MunicipioRepository")
 */
class Municipio
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $codigo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nome;

    /**
     * @ORM\Column(type="string", length=2)
     */
    private $uf;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Address", mappedBy="municipio")
     */
    private $endereco;

    public function __construct()
    {
        $this->endereco = new ArrayCollection();
    }

    public function serialize(): array
    {
        return get_object_vars($this);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getCodigo(): ?int
    {
        return $this->codigo;
    }

    public function setCodigo(int $codigo): self
    {
        $this->codigo = abs($codigo);

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

    public function removeEndereco(Address $endereco): self
    {
        if ($this->endereco->contains($endereco)) {
            $this->endereco->removeElement($endereco);

            if ($endereco->getMunicipio() === $this) {
                $endereco->setMunicipio(null);
            }
        }

        return $this;
    }
}
