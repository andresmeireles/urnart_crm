<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AddressRepository")
 */
class Address
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $road;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $neighborhood;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $number;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $zipcode;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Estado", inversedBy="endereco")
     */
    private $estado;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Municipio", inversedBy="endereco")
     */
    private $municipio;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\PessoaFisica", inversedBy="address")
     * @Orm\JoinColumn(name="pessoaFisicaId", referencedColumnName="id", onDelete="CASCADE")
     */
    private $pessoaFisicaId;

    public function getId()
    {
        return $this->id;
    }

    public function getRoad(): ?string
    {
        return $this->road;
    }

    public function setRoad(?string $road): self
    {
        $this->road = $road;

        return $this;
    }

    public function getNeighborhood(): ?string
    {
        return $this->neighborhood;
    }

    public function setNeighborhood(?string $neighborhood): self
    {
        $this->neighborhood = $neighborhood;

        return $this;
    }

    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(?string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): self
    {
        $zipcode = null === $zipcode ? null : str_replace('.', '', str_replace('-', '', $zipcode));
        $zipcode = (int) $zipcode;
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getEstado(): ?Estado
    {
        return $this->estado;
    }

    public function setEstado(?Estado $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    public function getMunicipio() : ?Municipio
    {
        return $this->municipio;
    }

    public function setMunicipio(?Municipio $municipio) : self
    {
        $this->municipio = $municipio;

        return $this;
    }

    /**
     * Get the value of pessoaFisicaId
     */
    public function getPessoaFisicaId(): ?PessoaFisica
    {
        return $this->pessoaFisicaId;
    }

    /**
     * Set the value of pessoaFisicaId
     *
     * @param   PessoaFisica $pessoaFisicaId
     * @return  self
     */
    public function setPessoaFisicaId(?PessoaFisica $pessoaFisicaId): self
    {
        $this->pessoaFisicaId = $pessoaFisicaId;

        return $this;
    }
}
