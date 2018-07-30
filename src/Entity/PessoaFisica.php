<?php
namespace App\Entity;

use App\Entity\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
     * @ORM\OneToMany(targetEntity="App\Entity\Phone", mappedBy="owner")
     */
    private $phones;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Email", mappedBy="owner")
     */
    private $emails;

    public function __construct()
    {
        parent::__construct();
        $this->phones = new ArrayCollection();
        $this->emails = new ArrayCollection();
    }

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

    /**
     * @return Collection|Phone[]
     */
    public function getPhones(): Collection
    {
        return $this->phones;
    }

    public function addPhone(Phone $phone): self
    {
        if (!$this->phones->contains($phone)) {
            $this->phones[] = $phone;
            $phone->setOwner($this);
        }

        return $this;
    }

    public function removePhone(Phone $phone): self
    {
        if ($this->phones->contains($phone)) {
            $this->phones->removeElement($phone);
            // set the owning side to null (unless already changed)
            if ($phone->getOwner() === $this) {
                $phone->setOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Email[]
     */
    public function getEmails(): Collection
    {
        return $this->emails;
    }

    public function addEmail(Email $email): self
    {
        if (!$this->emails->contains($email)) {
            $this->emails[] = $email;
            $email->setOwner($this);
        }

        return $this;
    }

    public function removeEmail(Email $email): self
    {
        if ($this->emails->contains($email)) {
            $this->emails->removeElement($email);
            // set the owning side to null (unless already changed)
            if ($email->getOwner() === $this) {
                $email->setOwner(null);
            }
        }

        return $this;
    }
}