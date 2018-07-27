<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PessoaJuridicaRepository")
 */
class PessoaJuridica
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
}
