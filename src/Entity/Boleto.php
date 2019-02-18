<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BoletoRepository")
 */
class Boleto extends BaseEntity
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
    private $BoletoCustomerOwner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $BoletoNumber;

    /**
     * @ORM\Column(type="integer")
     */
    private $BoletoVencimento;

    /**
     * @ORM\Column(type="smallint")
     */
    private $BoletoStatus;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $BoletoPaymentDate;

    /**
     * @ORM\Column(type="float")
     */
    private $boletoValue;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoletoCustomerOwner(): ?string
    {
        return $this->BoletoCustomerOwner;
    }

    public function setBoletoCustomerOwner(string $BoletoCustomerOwner): self
    {
        $this->BoletoCustomerOwner = $BoletoCustomerOwner;

        return $this;
    }

    public function getBoletoNumber(): ?string
    {
        return $this->BoletoNumber;
    }

    public function setBoletoNumber(string $BoletoNumber): self
    {
        $this->BoletoNumber = $BoletoNumber;

        return $this;
    }

    public function getBoletoVencimento(): ?int
    {
        return $this->BoletoVencimento;
    }

    public function setBoletoVencimento(int $BoletoVencimento): self
    {
        $this->BoletoVencimento = $BoletoVencimento;

        return $this;
    }

    public function getBoletoStatus(): ?int
    {
        return $this->BoletoStatus;
    }

    public function setBoletoStatus(int $BoletoStatus): self
    {
        $this->BoletoStatus = $BoletoStatus;

        return $this;
    }

    public function getBoletoPaymentDate(): ?\DateTimeInterface
    {
        return $this->BoletoPaymentDate;
    }

    public function setBoletoPaymentDate(?\DateTimeInterface $BoletoPaymentDate): self
    {
        $this->BoletoPaymentDate = $BoletoPaymentDate;

        return $this;
    }

    public function getBoletoValue(): ?float
    {
        return $this->boletoValue;
    }

    public function setBoletoValue(float $boletoValue): self
    {
        $this->boletoValue = $boletoValue;

        return $this;
    }
}
