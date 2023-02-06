<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\BaseEntity;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Utils\Andresmei\StdResponse;
use App\Utils\Andresmei\StringConvertions;
use App\Utils\Exceptions\CustomException;

final class ReportModel extends Model
{
    /**
     * @param string $entity
     * @param array $data
     * @return FlashResponse
     * @throws CustomException
     */
    public function createGenericReport(string $entity, array $data): FlashResponse
    {
        $flashResult = '';
        switch ($entity) {
            case 'boleto':
                $boletoModel = new BoletoModel($this->entityManager);
                $flashResult = $boletoModel->createBoletoRegistry($data);
                break;
            case 'productionCount':
                $flashResult = $this->createGenericRegistryArray($entity, $data);
                break;
            default:
                $flashResult = new FlashResponse(301, 'warning', sprintf(
                    'Impossível prosseguir, relátorio relacionado a %s não existe.',
                    $entity
                ));
                break;
        }

        return $flashResult;
    }

    /**
     * @param object $entity
     * @param array $data
     * @throws \ReflectionException
     */
    public function executeActionOnGenericEntityWithData(object $entity, array $data): void
    {
        try {
            $this->entityAndDataAction($entity, $data);
        } catch (\RuntimeException $err) {
            throw new \RuntimeException(
                sprintf(
                    'Erro: %s. Arquivo: %s. Linha: %s',
                    $err->getMessage(),
                    $err->getFile(),
                    $err->getLine()
                )
            );
        }
    }

    /**
     * @param object $entity
     * @param array $entityInformation
     * @throws \ReflectionException
     */
    public function entityAndDataAction(object $entity, array $entityInformation): void
    {
        $objectManager = $this->entityManager;
        foreach ($entityInformation as $key => $value) {
            $methodName = sprintf('set%s', ucfirst($key));
            if (!method_exists($entity, $methodName)) {
                continue;
            }
            $reflectFunc = new \ReflectionClass($entity);
            $functionParameter = $reflectFunc->getMethod($methodName)->getParameters()[0]->getType();
            $typeForConversion = $functionParameter === null ?
                'none' :
                $functionParameter->getName();
            $rightTypeValue = (new StringConvertions())->convertValue($typeForConversion, $value);
            $entity->{$methodName}($rightTypeValue);
        }
        $objectManager->persist($entity);
        $objectManager->flush();
    }

    /**
     * @param BaseEntity $entityToDeactive
     */
    public function deactiveGenericEntity(BaseEntity $entityToDeactive): void
    {
        $entityToDeactive->setActive(false);
        try {
            $this->entityManager->persist($entityToDeactive);
            $this->entityManager->flush();
        } catch (\PDOException $err) {
            throw new \PDOException($err->getMessage());
        }
    }

    /**
     * @param string $entityName
     * @param array $data
     * @return FlashResponse
     * @throws CustomException
     */
    public function createGenericRegistryArray(string $entityName, array $data): FlashResponse
    {
        $entity = sprintf('App\Entity\%s', ucfirst($entityName));
        $objectManager = $this->entityManager;
        $info = new NestedArraySeparator($data);
        $entityData = $info->getArrayInArray();
        try {
            array_walk($entityData, static function ($entityInformation) use (
                &$entity,
                &$objectManager
            ) {
                $class = new $entity();
                foreach ($entityInformation as $key => $value) {
                    $methodName = sprintf('set%s', ucfirst($key));
                    $reflectFunc = new \ReflectionClass($class);
                    $functionParameter = $reflectFunc->getMethod($methodName)->getParameters()[0]->getType();
                    $typeForConversion = $functionParameter === null ?
                        'none' :
                        $functionParameter->getName();
                    $rightTypeValue = (new StringConvertions())->convertValue($typeForConversion, $value);
                    $class->{$methodName}($rightTypeValue);
                }
                $objectManager->persist($class);
            });
            $objectManager->flush();
        } catch (\Exception $e) {
            throw new CustomException(
                sprintf(
                    '%s, %s, %s',
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                )
            );
        }

        return new FlashResponse(
            200,
            'success',
            'Ação concluida com sucesso.'
        );
    }



    /**
     * @param string $entity
     * @param int $consultId
     * @param array $data
     * @return FlashResponse
     * @throws \PDOException
     */
    public function editRegistryGeneric(
        string $entity,
        int $consultId,
        array $data
    ): FlashResponse {
        $entityManager = $this->entityManager;
        $entityClass = sprintf('App\Entity\%s', ucwords($entity));
        $entityToEdit = $entityManager->getRepository($entityClass)->find($consultId);
        try {
            foreach ($data as $key => $value) {
                $methodName = sprintf('set%s', ucwords($key));
                if (! method_exists($entityToEdit, $methodName)) {
                    continue;
                }
                if (strpos($value, '.') !== false) {
                    $value = (float) $value;
                }
                if (ctype_digit($value)) {
                    $value = (int) $value;
                }
                $entityToEdit->{$methodName}($value);
            }
            $entityManager->merge($entityToEdit);
            $entityManager->flush();
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), $e->getCode());
        }

        return new FlashResponse(200, 'success', sprintf(
            'Titulo %s/%s atualizado com sucesso.',
            $entityToEdit->getBoletoNumber(),
            $entityToEdit->getBoletoInstallment()
        ));
    }

    /**
     * @param string $entity
     * @param string $typeOfOrder
     * @return StdResponse
     * @throws \BadMethodCallException
     */
    public function getGenericList(string $entity, string $typeOfOrder): StdResponse
    {
        $order = '';
        $list = '';
        switch ($typeOfOrder) {
            case 'last':
                $list = 'Ultimos Títulos';
                $order = ['createDate' => 'ASC'];
                break;
            case 'client':
                $list = 'Títulos por cliente';
                if ($entity === 'boleto') {
                    $order = ['boletoCustomerOwner' => 'ASC'];
                }
                break;
            case 'byDate':
            case 'beginDate':
                $responseObject = new StdResponse();
                $responseObject->typeOfList = 'Busca de títulos por data';
                $responseObject->consultResults = null;
                return $responseObject;
            default:
                throw new \BadMethodCallException(sprintf(
                    'Order %s não é um parametro valido. Favor enviar parametro válido',
                    $order
                ));
                break;
        }
        $entity = sprintf('App\Entity\%s', ucwords($entity));
        $returnObject = new StdResponse();
        $returnObject->typeOfList = $list;
        $returnObject->consultResults = $this->entityManager
            ->getRepository($entity)
            ->findBy([], $order);

        return $returnObject;
    }

    /**
     * @param string $entity
     * @param int    $consultId
     * @return string
     */
    public function serializedGenericConsult(string $entity, int $consultId): string
    {
        $result = $this->entityManager->getRepository($entity)->find($consultId);

        return $this->serializer->serialize($result, 'json');
    }

    /****************************************************
    ************** SPECIFIC ENTITY METHODS **************
    *****************************************************/

    /**
     * @param string $entity
     * @param string $whereField
     * @param string|null $requiredFields
     * @param string|null $startDate
     * @param string|null $endingDate
     * @return array
     * @throws \Exception
     */
    public function searchByDate(
        string $entity,
        string $whereField,
        ?string $requiredFields,
        ?string $startDate,
        ?string $endingDate
    ): array {
        return $this->getGenericListByDate($entity, $whereField, $requiredFields, $startDate, $endingDate);
    }
}
