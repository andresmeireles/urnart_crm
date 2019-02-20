<?php

namespace App\Model;

use App\Utils\Andresmei\FlashResponse;
use App\Entity\Boleto;
use App\Utils\Andresmei\StdResponse;

class ReportModel extends Model
{
    /**
     * Select the create function and do the action.
     *
     * @param string    $entity
     * @param array     $data
     * @return FlashResponse
     */
    public function createGenericReport(string $entity, array $data): FlashResponse
    {
        $flashResult = '';

        switch ($entity) {
            case 'boleto':
                $flashResult = $this->createBoletoRegistry($data);
                break;
            default:
                $flashResult = new FlashResponse(301, 'warning', sprintf('Impossível prosseguir, relátorio relacionado a %s não existe.', $entity));
                break;
        }

        return $flashResult;
    }

    public function createBoletoRegistry(array $data): FlashResponse
    {
        $entity = new Boleto;
        $entityManager = $this->em;

        $entityManager->getConnection()->beginTransaction();

        try {
            $entity->setBoletoCustomerOwner($data['boletoCustomerOwner']);
            $entity->setBoletoNumber($data['boletoNumber']);
            $entity->setBoletoInstallment($data['boletoInstallment']);
            $entity->setBoletoValue($data['boletoValue']);
            $entity->setBoletoVencimento($data['boletoVencimento']);
            $entity->setActive(true);

            $this->em->persist($entity);
            $this->em->flush();
            $this->em->getConnection()->commit();

            $httpCode = 200;
            $type = 'success';
            $message = sprintf('Peido de %s numero %s/%d criado com sucesso!', $data['boletoCustomerOwner'], $data['boletoNumber'], $data['boletoInstallment']);
        } catch (\PDOException $e) {
            $this->em->getConnection()->rollback();
            $httpCode = 301;
            $type = 'danger';
            $message = 'Peido não foi criado.';
        }

        return new FlashResponse($httpCode, $type, $message);
    }

    public function editRegistryGeneric(string $entity, int $consultId, array $data): FlashResponse
    {
        $entityManager = $this->em;

        $entityClass = sprintf('App\Entity\%s', ucwords($entity));
        $entityToEdit = $entityManager->getRepository($entityClass)->find($consultId);
        
        $entityManager->getConnection()->beginTransaction();

        try {
            foreach ($data as $key => $value) {
                $methodName = sprintf('set%s', ucwords($key));
                if (!method_exists($entityToEdit, $methodName)) {
                    continue;
                }

                if (strpos($value, '.') !== false ) {
                    $value = (float) $value;
                }

                if (ctype_digit($value)) {
                    $value = (int) $value;
                }

                $entityToEdit->$methodName($value);
            }
            $entityManager->merge($entityToEdit);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (\PDOException $e) {
            $entityManager->getConnection()->rollback();
            throw new \PDOException($e->getMessage(), $e->getCode());
        }

        return new FlashResponse(200, 'success', sprintf('Item %s atualizado com sucesso.', $entityToEdit->getId()));
    }
    
    public function getGenericList(string $entity, string $typeOfOrder): StdResponse
    {
        $order = '';
        $list = '';
        
        switch ($typeOfOrder) {
            case 'last':
                $list = 'Ultimos Titulos';
                $order = array('createDate' => 'ASC');
                break;
            case 'client':
                $list = 'Titulos por cliente';
                if ($entity === 'boleto') {
                    $order = array('boletoCustomerOwner' => 'ASC');
                }
                break;
            default:
                throw new \BadMethodCallException(sprintf('Order %s não é um parametro valido. Favor enviar parametro válido', $order));
                break;
        }

        $entity = sprintf('App\Entity\%s', ucwords($entity));

        $returnObject = new StdResponse;
        $returnObject->typeOfList = $list;
        $returnObject->consultResults = $this->em->getRepository($entity)->findBy(array(), $order); 

        return $returnObject;
    }

    /**
     * Return a serializaed register from database.
     *
     * @param   string  $entity     Entity that will be consulted.      
     * @param   int     $consultId  Id to consult registry.
     *
     * @return  string              Json string.
     */
    public function serializedGenericConsult(string $entity, int $consultId): string
    {
        $result = $this->em->getRepository($entity)->find($consultId);
        $jsonResult = $this->serializer->serialize($result, 'json');
        return $jsonResult;
    }

    /**************************************************** 
    ************** SPECIFIC ENTITY METHODS **************
    *****************************************************/

    /**
     * Boleto entity specific. Change status and do some operation.
     *
     * @param   int       $boletoId    Identificaction of Boleto entity
     * @param   array     $boletoData  Status and date info.
     *
     * @return  FlashResponse               FlashResponse object.
     */
    public function boletoChangeStatus(int $boletoId, array $boletoData): FlashResponse
    {
        $entityManager = $this->em;

        $boletoRegistry = $entityManager->getRepository(Boleto::class)->find($boletoId);

        if (is_null($boletoRegistry)) {
            throw new \PDOException('Não é uma instancia de Boleto entity.');
        }

        try {
            $status = (int) $boletoData['boletoStatus'];
            $boletoRegistry->setBoletoStatus($status);

            if ($status === 1) {
                $boletoRegistry->setBoletoPaymentDate($boletoData['boletoPaymentDate']);
                $boletoRegistry->setActive(false);
            }

            $entityManager->merge($boletoRegistry);
            $entityManager->flush();

        } catch (\PDOException $e) {
            throw new \PDOException(sprintf('Error Processing Request. %s', $e->getMessage()), 1);
        }

        return new FlashResponse(
            200, 
            'success', 
            sprintf('Titulo %s/%d teve seu staus atualizado com sucesso.', $boletoRegistry->getBoletoNumber(), $boletoRegistry->getBoletoInstallment())
        );
    }
}