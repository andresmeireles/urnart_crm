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

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $boletoProvisionamentoDate;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $boletoPorContaStatus = [];

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
        $this->setLastUpdate();
        $this->boletoCustomerOwner = $boletoCustomerOwner;

        return $this;
    }

    public function getBoletoNumber(): ?string
    {
        return $this->boletoNumber;
    }

    public function setBoletoNumber(string $boletoNumber): self
    {
        $this->setLastUpdate();
        $this->boletoNumber = $boletoNumber;

        return $this;
    }

    public function getBoletoStatus(): ?int
    {
        return $this->boletoStatus;
    }

    public function getBoletoStatusExtense(): ?string
    {
        switch ($this->boletoStatus) {
            case 0:
                return 'Não Pago';
                break;
            case 1:
                return 'Pago';
                break;
            case 2:
                return 'Pago em atraso';
                break;
            case 3:
                return 'Pgto. Provisionado';
                break;
            case 4:
                return 'Pgto. por Conta';
                break;
        }
    }

    public function setBoletoStatus(int $boletoStatus): self
    {
        $this->setLastUpdate();
        $this->boletoStatus = $boletoStatus;

        return $this;
    }

    public function getBoletoPaymentDate(): ?\DateTimeInterface
    {
        return $this->boletoPaymentDate;
    }

    public function setBoletoPaymentDate(?string $boletoPaymentDate): self
    {
        $this->setLastUpdate();
        $date = !is_null($boletoPaymentDate) ? new \DateTime($boletoPaymentDate) : $boletoPaymentDate;

        if (!($date instanceof \DateTime) and !is_null($date)) {
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
        $this->setLastUpdate();
        $this->boletoValue = $boletoValue;

        return $this;
    }

    public function getBoletoInstallment(): ?int
    {
        return $this->boletoInstallment;
    }

    public function setBoletoInstallment(int $boletoInstallment): self
    {
        $this->setLastUpdate();
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
        $this->setLastUpdate();
        $date = new \DateTime($boletoVencimento);

        if (!($date instanceof \DateTime)) {
            throw new InvalidParameterException(sprintf('Parametro [%s] não é valido. Preciso enviar instancia de \DateTime.', $date));
        }

        $this->boletoVencimento = $date;

        return $this;
    }

    public function getBoletoProvisionamentoDate(): ?\DateTimeInterface
    {
        return $this->boletoProvisionamentoDate;
    }

    public function setBoletoProvisionamentoDate(?\DateTimeInterface $boletoProvisionamentoDate): self
    {
        $this->setLastUpdate();
        $this->boletoProvisionamentoDate = $boletoProvisionamentoDate;

        return $this;
    }

    public function getBoletoPorContaStatus(): ?array
    {
        return $this->boletoPorContaStatus;
    }

    public function setBoletoPorContaStatus(?array $boletoPorContaStatus): self
    {
        $this->setLastUpdate();
        $this->boletoPorContaStatus = $boletoPorContaStatus;

        return $this;
    }
}
