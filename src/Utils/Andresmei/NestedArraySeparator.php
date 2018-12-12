<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

class NestedArraySeparator
{
    /**
     * Array associativo com os array simples
     *
     * @var array
     */
    private $simpleArray;

    /**
     * Array asspciativo com os arrays de arrays
     *
     * @var array
     */
    private $arrayInArray;

    function __construct(array $nestedArray)
    {
        foreach ($nestedArray as $key => $value) {
            if (!is_array($value)) {
                $this->setSimpleArray($key, $value);
                continue;
            }
            $this->setArrayInArray($value);
        }
    }

    private function setSimpleArray(string $key, string $value): void
    {
        $this->simpleArray[$key] = $value;
    }

    public function getSimpleArray(): array
    {
        return $this->simpleArray;
    }

    private function setArrayInArray(array $value): void
    {
        $this->arrayInArray[] = $value;
    }

    public function getArrayInArray(): array
    {
        return $this->arrayInArray;
    }
}