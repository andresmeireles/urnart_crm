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
     * @ORM\Column(type="string", length=255)
     */
    private $nomeFantasia;

    /**
     * @ORM\Column(type="integer")
     */
    private $inscricaoEstadual;

    /**
     * @ORM\Column(type="integer")
     */
    private $inscricaoMunicipal;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cnpj;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $inscriçãoEstadual;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $inscriçãoMunicipal;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $dataDeFundação;

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

    /**
     * Get the value of dataDeFundação
     */ 
    public function getDataDeFundação()
    {
        return $this->dataDeFundação;
    }

    /**
     * Set the value of dataDeFundação
     *
     * @return  self
     */ 
    public function setDataDeFundação(?\DateTime $dataDeFundação)
    {
        $this->dataDeFundação = $dataDeFundação;

        return $this;
    }

    /**
     * Get the value of inscriçãoMunicipal
     */ 
    public function getInscriçãoMunicipal()
    {
        return $this->inscriçãoMunicipal;
    }

    /**
     * Set the value of inscriçãoMunicipal
     *
     * @return  self
     */ 
    public function setInscriçãoMunicipal(integer $inscriçãoMunicipal): ?self
    {
        $this->inscriçãoMunicipal = $inscriçãoMunicipal;

        return $this;
    }

    /**
     * Get the value of nomeFantasia
     */ 
    public function getNomeFantasia()
    {
        return $this->nomeFantasia;
    }

    /**
     * Set the value of nomeFantasia
     *
     * @return  self
     */ 
    public function setNomeFantasia($nomeFantasia)
    {
        $this->nomeFantasia = $nomeFantasia;

        return $this;
    }

    /**
     * Get the value of inscriçãoEstadual
     */ 
    public function getInscriçãoEstadual()
    {
        return $this->inscriçãoEstadual;
    }

    /**
     * Set the value of inscriçãoEstadual
     *
     * @return  self
     */ 
    public function setInscriçãoEstadual($inscriçãoEstadual)
    {
        $this->inscriçãoEstadual = $inscriçãoEstadual;

        return $this;
    }
}
