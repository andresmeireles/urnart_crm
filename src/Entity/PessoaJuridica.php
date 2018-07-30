<?php
namespace App\Entity;

use App\Entity\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PessoaJuridicaRepository")
 */
class PessoaJuridica extends BaseEntity
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
    private $razaoSocial;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Proprietario", mappedBy="empresas")
     */
    private $proprietarios;

    public function __construct()
    {
        parent::__construct();
        $this->proprietarios = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRazaoSocial(): ?string
    {
        return $this->razaoSocial;
    }

    public function setRazaoSocial(string $razaoSocial): self
    {
        $this->razaoSocial = $razaoSocial;

        return $this;
    }

    /**
     * @return Collection|Proprietario[]
     */
    public function getProprietarios(): Collection
    {
        return $this->proprietarios;
    }

    public function addProprietario(Proprietario $proprietario): self
    {
        if (!$this->proprietarios->contains($proprietario)) {
            $this->proprietarios[] = $proprietario;
            $proprietario->addEmpresa($this);
        }

        return $this;
    }

    public function removeProprietario(Proprietario $proprietario): self
    {
        if ($this->proprietarios->contains($proprietario)) {
            $this->proprietarios->removeElement($proprietario);
            $proprietario->removeEmpresa($this);
        }

        return $this;
    }
}
