<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Routing\Exception\InvalidParameterException;

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
    private $boletoCustomerOwner;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $boletoNumber;

    /**
     * @ORM\Column(type="smallint")
     * 
     * Status possíveis.
     * 0 - Sem previsão
     * 1 - Pago
     * 2 - Pago em atraso
     * 3 - Com previsão
     * 4 - Pagamento por conta.
     */
    private $boletoStatus = 0;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $boletoPaymentDate;

    /**
     * @ORM\Column(type="float")
     */
    private $boletoValue;

    /**
     * @ORM\Column(type="integer")
     */
    private $boletoInstallment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $boletoVencimento;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBoletoCustomerOwner(): ?string
    {
        return $this->boletoCustomerOwner;
    }

    public function setBoletoCustomerOwner(string $boletoCustomerOwner): self
    {
        $this->boletoCustomerOwner = $boletoCustomerOwner;

        return $this;
    }

    public function getBoletoNumber(): ?string
    {
        return $this->boletoNumber;
    }

    public function setBoletoNumber(string $boletoNumber): self
    {
        $this->boletoNumber = $boletoNumber;

        return $this;
    }

    public function getBoletoStatus(): ?int
    {
        return $this->boletoStatus;
    }

    public function setBoletoStatus(int $boletoStatus): self
    {
        $this->boletoStatus = $boletoStatus;

        return $this;
    }

    public function getBoletoPaymentDate(): ?\DateTimeInterface
    {
        return $this->boletoPaymentDate;
    }

    public function setBoletoPaymentDate(?string $boletoPaymentDate): self
    {
        $date = !is_null($boletoPaymentDate) ? new \DateTime($boletoPaymentDate) : $boletoPaymentDate;

        if (!($date instanceOf \DateTime) and !is_null($date)) {
            throw new InvalidParameterException(sprintf('Parametro [%s] não é valido. Valores reconhecidos são instancia de \DateTIme e null', $date));
        } 

        $this->boletoPaymentDate = $date;

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

    public function getBoletoInstallment(): ?int
    {
        return $this->boletoInstallment;
    }

    public function setBoletoInstallment(int $boletoInstallment): self
    {
        $this->boletoInstallment = $boletoInstallment;

        return $this;
    }

    public function getBoletoVencimento(): ?\DateTimeInterface
    {
        return $this->boletoVencimento;
    }

    /* public function setBoletoVencimento(\DateTimeInterface $boletoVencimento): self
    {
        $this->boletoVencimento = $boletoVencimento;

        return $this;
    } */

    public function setBoletoVencimento(string $boletoVencimento): self
    {
        $date = new \DateTime($boletoVencimento);

        if (!($date instanceOf \DateTime)) {
            throw new InvalidParameterException(sprintf('Parametro [%s] não é valido. Preciso enviar instancia de \DateTime.', $date));
        } 

        $this->boletoVencimento = $date;

        return $this;
    }
}
