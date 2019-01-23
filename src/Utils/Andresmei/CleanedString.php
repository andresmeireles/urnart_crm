<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

class CleanedString
{
    /**
     * varable to store clenaed string
     *
     * @var string
     */
    private $cleanedString;

    public function __construct(string $unCleanedString, string $symbols) {
        $this->cleanedString = trim(str_replace($symbols, '', $unCleanedString));
    }

    public function getCleanString(): string
    {
        return $this->cleanedString;
    }
}
