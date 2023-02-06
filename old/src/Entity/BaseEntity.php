<?php declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\SerializerBuilder;

/**
 * @ORM\MappedSuperclass()
 */
abstract class BaseEntity
{
    /**
     * @ORM\Column(type="datetimetz")
     */
    protected $lastUpdate;

    // Vai ser um campo usuario
    //private $editBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = true;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $createDate;

    public function __construct()
    {
        $this->createDate = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->lastUpdate = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
    }

    /**
     * @return array|null
     */
    public function __toArray(): ?array
    {
        return get_object_vars($this);
    }

    public function __toString(): ?string
    {
        $serializer = SerializerBuilder::create()->build();
        return $serializer->serialize($this, 'json');
    }

    public function getCreateDate(): ?string
    {
        return $this->createDate instanceof \DateTime ? $this->createDate->format('d-m-Y') : null;
    }

    public function getCreateDateObject(): ?\DateTimeInterface
    {
        return $this->createDate;
    }

    public function setCreateDate(\DateTimeInterface $date): self
    {
        $this->createDate = $date;

        return $this;
    }

    public function getLastUpdate(): ?string
    {
        return $this->lastUpdate->format('d-m-Y');
    }

    public function setLastUpdate(): self
    {
        $this->lastUpdate = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));

        return $this;
    }

    public function getActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): self
    {
        $this->active = $active;

        return $this;
    }
}
