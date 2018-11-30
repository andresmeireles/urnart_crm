<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\SerializerBuilder;

/**
 * @ORM\MappedSuperclass()
 */
abstract class BaseEntity
{
    // Vai ser um campo usuario
    //private $editBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = false;

    /**
     * @ORM\Column(type="datetimetz")
     */
    private $createDate;

    /**
     * @ORM\Column(type="datetimetz")
     */
    protected $lastUpdate;

    public function __construct()
    {
        $this->createDate = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        $this->lastUpdate = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
    }

    protected function __toArray()
    {
        return get_object_vars($this);
    }

    public function __toString(): ?string
    {
        $serializer = SerializerBuilder::create()->build();
        $result = $serializer->serialize($this, 'json');
        return $result;
    }

    public function getCreateDate(): ?string
    {
        $date = ($this->createDate instanceof \DateTime) ? $this->createDate->format('d-m-Y') : null;
        return $date;
    }

    public function getLastUpdate(): ?string
    {
        return $this->lastUpdate->format('d-m-Y');
    }

    public function setLastUpdate(): self
    {
        $this->lastUpdate = new \DateTime('now', new \DateTimeZone('America/Sao_Paulo'));
        ;

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
