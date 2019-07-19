<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;

use Doctrine\Common\Annotations\AnnotationReader;

/**
 * Class RespectValidationAnnotation
 * @package App\Utils\Andresmei
 */
final class RespectValidationAnnotation
{
    private $allValidationErrors;

    /**
     * @param object $class
     * @return array|null
     * @throws \Doctrine\Common\Annotations\AnnotationException
     * @throws \ReflectionException
     */
    public function executeClassValidation(object $class): ?array
    {
        $firstValidationError = [];
        $reader = new AnnotationReader();
        $reflectionClass = new \ReflectionClass($class);
        $props = $reflectionClass->getProperties();
        foreach ($props as $prop) {
            $reflecProp = new \ReflectionProperty($class, $prop->getName());
            $getMethodName = sprintf('get%s', ucfirst($reflecProp->getName()));
            $validations = $reader->getPropertyAnnotation($reflecProp, ValidationAnnotation::class);
            if ($validations instanceof ValidationAnnotation) {
                $validations->executeValidationInParameter([$reflecProp->getName() => $class->{$getMethodName}()]);
                $firstValidationError[] = $validations->getValidationErrors();
                $this->allValidationErrors[] = $validations->getAllValidationErrors();
            }
        }

        return $this->clearNullValues($firstValidationError);
    }

    /**
     * @param array $validationWithNullValues
     * @return array|null
     */
    private function clearNullValues(array $validationValues): ?array
    {
        foreach ($validationValues as $key => $value) {
            if ($value === null) {
                unset($validationValues[$key]);
            }
        }

        return $validationValues === [] ? null : $validationValues;
    }


    /**
     * @return array|null
     */
    public function getAllErrorMessages(): ?array
    {
        return $this->clearNullValues($this->allValidationErrors);
    }

}