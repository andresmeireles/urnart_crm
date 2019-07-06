<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyRepository")
 */
final class Survey extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $surveyType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PessoaJuridica", inversedBy="surveys")
     * @ORM\JoinColumn(nullable=true)
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=3000, nullable=true)
     */
    private $answer;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $surveyReferenceDate;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $customerName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurveyType(): ?string
    {
        return $this->surveyType;
    }

    public function setSurveyType(string $surveyType): self
    {
        $this->surveyType = $surveyType;

        return $this;
    }

    public function getCustomer(): ?PessoaJuridica
    {
        return $this->customer;
    }

    public function setCustomer(?PessoaJuridica $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }

    public function getSurveyReferenceDate(): ?string
    {
        return $this->surveyReferenceDate;
    }

    public function setSurveyReferenceDay(string $surveyReferenceDate): self
    {
        $this->surveyReferenceDate = $surveyReferenceDate;

        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): self
    {
        $this->customerName = $customerName;

        return $this;
    }
}
