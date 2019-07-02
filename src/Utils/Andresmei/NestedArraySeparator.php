<?php
declare(strict_types = 1);

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

    /**
     * Array Associativo com divisões
     *
     * @var array
     */
    private $associativeArray;

    public function __construct(array $nestedArray)
    {
        foreach ($nestedArray as $key => $value) {
            if (!is_array($value)) {
                $this->setSimpleArray($key, $value);
                continue;
            }
            $this->setArrayInArray($value);
        }

        $this->setArrayGroup($nestedArray);
    }

    public function getSimpleArray(): array
    {
        return $this->simpleArray;
    }

    public function getArrayInArray(): array
    {
        return $this->arrayInArray;
    }
    
    private function setSimpleArray(string $key, string $value): void
    {
        $this->simpleArray[$key] = $value;
    }
    
    private function setArrayInArray(array $value): void
    {
        $this->arrayInArray[] = $value;
    }

    private function setArrayGroup(array $value): void
    {
        $keyName = '';
        $arrayResult = [];
        foreach ($value as $key => $val) {
            if (!is_array($val)) {
                continue;
            }

            $cleanedName = preg_replace('/[0-9]+/', '', $key);

            if ($cleanedName !== $keyName) {
                $keyName = $cleanedName;
            }

            $arrayResult[$keyName][] = $val;
        }

        $this->associativeArray = $arrayResult;
    }

    public function getAssoativeArray(): array
    {
        return $this->associativeArray;
    }

    public function getAssoativeArrayGroup(string $name): ?array
    {
        return $this->associativeArray[$name] ?? null;
    }
}
