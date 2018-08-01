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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomeFantasia;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $inscricaoEstadual;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $inscricaoMunicipal;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cnpj;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $situacaoCadastral;

    /**
     * @ORM\Column(type="datetimetz", nullable=true)
     */
    private $dataDeFundacao;

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

    public function setRazaoSocial(?string $razaoSocial): self
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
     * Get the value of dataDeFundacao
     */ 
    public function getDataDeFundacao(): ?\DateTime
    {
        return $this->dataDeFundacao->format('d-m-Y');
    }

    /**
     * Set the value of dataDeFundacao
     *
     * @return  self
     */ 
    public function setDataDeFundacao(?\DateTime $dataDeFundacao): ?self
    {
        $this->dataDeFundacao = $dataDeFundacao;

        return $this;
    }

    /**
     * Get the value of inscricaoMunicipal
     */ 
    public function getInscricaoMunicipal(): ?string
    {
        return $this->inscricaoMunicipal;
    }

    /**
     * Set the value of inscricaoMunicipal
     *
     * @return  self
     */ 
    public function setInscricaoMunicipal(?int $inscricaoMunicipal): ?self
    {
        $this->inscricaoMunicipal = $inscricaoMunicipal;

        return $this;
    }

    /**
     * Get the value of nomeFantasia
     */ 
    public function getNomeFantasia(): ?string
    {
        return $this->nomeFantasia;
    }

    /**
     * Set the value of nomeFantasia
     *
     * @return  self
     */ 
    public function setNomeFantasia(?string $nomeFantasia): ?self
    {
        $this->nomeFantasia = $nomeFantasia;

        return $this;
    }

    /**
     * Get the value of inscricaoEstadual
     */ 
    public function getInscricaoEstadual(): ?int
    {
        return $this->inscricaoEstadual;
    }

    /**
     * Set the value of inscricaoEstadual
     *
     * @return  self
     */ 
    public function setInscricaoEstadual(?int $inscricaoEstadual): ?self
    {
        $this->inscricaoEstadual = $inscricaoEstadual;

        return $this;
    }

    /**
     * Get the value of cnpj
     */ 
    public function getCnpj(): ?string  
    {
        return $this->cnpj;
    }

    /**
     * Set the value of cnpj
     *
     * @return  self
     */ 
    public function setCnpj(?string $cnpj): self
    {
        $this->cnpj = $cnpj;

        return $this;
    }

    /**
     * Get the value of situacaoCadastral
     */ 
    public function getSituacaoCadastral(): ?int
    {
        return $this->situacaoCadastral;
    }

    /**
     * Set the value of situacaoCadastral
     *
     * @return  self
     */ 
    public function setSituacaoCadastral(?int $situacaoCadastral): self
    {
        $this->situacaoCadastral = $situacaoCadastral;

        return $this;
    }
}
