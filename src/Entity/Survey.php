<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SurveyRepository")
 */
class Survey extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="smallint")
     */
    private $surveyType;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\PessoaJuridica", inversedBy="surveys")
     * @ORM\JoinColumn(nullable=false)
     */
    private $customer;

    /**
     * @ORM\Column(type="string", length=3000)
     */
    private $answer;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSurveyType(): ?int
    {
        return $this->surveyType;
    }

    public function setSurveyType(int $surveyType): self
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
}
