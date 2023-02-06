<?php

namespace App\Entity;

use App\Repository\OrderPredictionRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderPredictionRepository::class)
 */
class OrderPrediction
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=ManualOrderReport::class, cascade={"persist", "remove"})
     */
    private $orderId;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $predictionDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOrderId(): ?ManualOrderReport
    {
        return $this->orderId;
    }

    public function setOrderId(?ManualOrderReport $orderId): self
    {
        $this->orderId = $orderId;

        return $this;
    }

    public function getPredictionDate(): ?\DateTimeInterface
    {
        return $this->predictionDate;
    }

    public function getFormatedPredictionDate(): String
    {
        $date = $this->getPredictionDate();
        if ($date === null) {
            return 'SEM PREVISÃO DEFINIDA';
        }

        return $date->format('d/m/Y');
    }

    public function setPredictionDate(?\DateTimeInterface $predictionDate): self
    {
        $this->predictionDate = $predictionDate;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
