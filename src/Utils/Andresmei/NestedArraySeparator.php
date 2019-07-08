<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;

final class NestedArraySeparator
{
    /**
     * @var array
     */
    private $simpleArray;

    /**
     * @var array
     */
    private $arrayInArray;

    /**
     * @var array
     */
    private $associativeArray;

    public function __construct(array $nestedArray)
    {
        foreach ($nestedArray as $key => $value) {
            if (! is_array($value)) {
                $this->setSimpleArray($key, $value);
                continue;
            }
            $this->setArrayInArray($value);
        }

        $this->setArrayGroup($nestedArray);
    }

    /**
     * @return array
     */
    public function getSimpleArray(): array
    {
        return $this->simpleArray;
    }

    /**
     * @return array
     */
    public function getArrayInArray(): array
    {
        return $this->arrayInArray;
    }

    /**
     * @return array
     */
    public function getAssoativeArray(): array
    {
        return $this->associativeArray;
    }

    /**
     * @param string $name
     * @return array|null
     */
    public function getAssoativeArrayGroup(string $name): ?array
    {
        return $this->associativeArray[$name] ?? null;
    }

    /**
     * @param string $key
     * @param string $value
     */
    private function setSimpleArray(string $key, string $value): void
    {
        $this->simpleArray[$key] = $value;
    }

    /**
     * @param array $value
     */
    private function setArrayInArray(array $value): void
    {
        $this->arrayInArray[] = $value;
    }

    /**
     * @param array $value
     */
    private function setArrayGroup(array $value): void
    {
        $keyName = '';
        $arrayResult = [];
        foreach ($value as $key => $val) {
            if (! is_array($val)) {
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
}
