<?php
namespace App\Entity;

use App\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PessoaFisicaRepository")
 */
class PessoaFisica extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $lastName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $cpf;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $rg;

    /**
     * @ORM\Column(type="string")
     */
    private $genre = M;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $birthDate;

    /**
     * Get the value of firstName
     */ 
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @return  self
     */ 
    public function setFirstName(?string $firstName): ?self
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastname
     */ 
    public function getLastName(): ?string
    {
        return $this->lastname;
    }

    /**
     * Set the value of lastname
     *
     * @return  self
     */ 
    public function setLastName(?string $lastname): ?self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get the value of cpf
     */ 
    public function getCpf(): ?string
    {
        return $this->cpf;
    }

    /**
     * Set the value of cpf
     *
     * @return  self
     */ 
    public function setCpf(string $cpf): ?self
    {
        $this->cpf = $cpf;

        return $this;
    }

    /**
     * Get the value of rg
     */ 
    public function getRg(): ?string
    {
        return $this->rg;
    }

    /**
     * Set the value of rg
     *
     * @return  self
     */ 
    public function setRg(string $rg): ?self
    {
        $this->rg = $rg;

        return $this;
    }

    /**
     * Get the value of genre
     */ 
    public function getGenre(): ?string
    {
        return $this->genre;
    }

    /**
     * Set the value of genre
     *
     * @return  self
     */ 
    public function setGenre(?string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Get the value of birthDate
     */ 
    public function getBirthDate(): ? \DateTime
    {
        return $this->birthDate;
    }

    /**
     * Set the value of birthDate
     *
     * @return  self
     */ 
    public function setBirthDate(?\DateTime $birthDate): ?self
    {
        $this->birthDate = $birthDate;

        return $this;
    }
}