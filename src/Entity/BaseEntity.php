<?php 
namespace App\Entity;

/**
 * Base entity with some fields and methods, for audit
 */
abstract class BaseEntity
{
	/**
	 * @ORM\Column(type="datetimez")
	 */
	private $createDate;

	private $lastUpdate;

	private $editBy;

	public function __construct()
	{
		$this->createDate = new \DateTime('now', new DateTimeZone('America/Sao_Paulo'));
	}

	public function getCreateDate(): ?\DateTime
	{
		return $this->createDate;
	}

	public function getLastUpdate(): ?\DateTime
	{
		return $this->lastUpdate;
	}

	public function setLastUpdate(\DateTime $update): self 
	{
		$this->lastUpdate = $update;

		return $this;
	}
}
