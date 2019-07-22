<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProprietarioRepository")
 */
class Proprietario
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\PessoaJuridica", inversedBy="proprietarios")
     */
    private $empresas;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PessoaFisica", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="pessoaFisica", referencedColumnName="id")
     */
    private $pessoaFisica;

    public function __construct()
    {
        $this->empresas = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Collection|PessoaJuridica[]
     */
    public function getEmpresas(): Collection
    {
        return $this->empresas;
    }

    public function addEmpresa(PessoaJuridica $empresa): self
    {
        if (! $this->empresas->contains($empresa)) {
            $this->empresas[] = $empresa;
        }

        return $this;
    }

    public function removeEmpresa(PessoaJuridica $empresa): self
    {
        if ($this->empresas->contains($empresa)) {
            $this->empresas->removeElement($empresa);
        }

        return $this;
    }

    public function getPessoaFisica(): ?PessoaFisica
    {
        return $this->pessoaFisica;
    }

    public function setPessoaFisica(?PessoaFisica $pessoaFisica): self
    {
        $this->pessoaFisica = $pessoaFisica;

        return $this;
    }
}
