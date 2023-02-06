<?php

declare(strict_types=1);

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/** 
 * @MongoDB\Document 
 */
class Romaneio
{
    /**
     * @MongoDB\Id
     */
    private $id;

    /**
     * @MongoDB\Field(type="string")
     */
    private string $driverName;

    /**
     * @MongoDB\Field(type="string")
     */
    private string $type;

    /**
     * @MongoDB\Field(type="collection")
     */
    private array $items;

    /**
     * @MongoDB\Field(type="timestamp")
     */
    private \MongoDB\BSON\Timestamp $createDate;

    public function __construct(string $driverName, string $type, array $romaneioItems, int $createDate)
    {
        $this->driverName = $driverName;
        $this->type = $type;
        $this->items = $romaneioItems;
        $this->createDate = new \MongoDB\BSON\Timestamp(1, $createDate);
    }

    public function edit(string $driverName, string $type, array $romaneioItems, int $createDate = 0): self
    {
        $this->driverName = $driverName;
        $this->type = $type;
        $this->items = $romaneioItems;
        if ($createDate > 0) $this->createDate = new \MongoDB\BSON\Timestamp(1, $createDate);

        return $this;
    }

    public function getId(): string
    {
        return $this->id;
    }

    // public function setDriverName(string $driverName)
    // {
    //     $this->driverName = $driverName;

    //     return $this;
    // }

    public function getDriverName(): string
    {
        return $this->driverName;
    }

    // public function setItems(array $items)
    // {
    //     $this->items = $items;

    //     return $this;
    // }

    public function getType(): string
    {
        return $this->type;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    // public function setCreateDate(int $createDate)
    // {
    //     $this->createDate = $createDate;

    //     return $this;
    // }

    public function getCreateDate(): int
    {
        return $this->createDate->getTimestamp();
    }
}
