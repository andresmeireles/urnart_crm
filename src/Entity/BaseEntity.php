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
	private $lastUpdate;

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

	protected function getCreateDate(): ?\DateTime
	{
		return $this->createDate->format('d-m-Y');
	}

	protected function getLastUpdate(): ?\DateTime
	{
		return $this->lastUpdate->format('d-m-Y');
	}

	protected function setLastUpdate(\DateTimeInterface $update): self 
	{
		$this->lastUpdate = $update;

		return $this;
	}

	protected function getActive(): bool
	{
		return $this->active;
	}

	protected function setActive(bool $active): self 
	{
		$this->active = $active;

		return $this;
	}
}
