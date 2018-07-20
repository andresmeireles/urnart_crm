<?php 
namespace App\Entity;

/**
 * Base entity with some fields and methods, for audit
 */
abstrat class BaseEntity
{
	/**
	 * @ORM\Column(type="date")
	 */
	private $createDate = new \DateTime('now');

	private $lastUpdate;

	private $editBy;

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
