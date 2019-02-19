<?php

namespace App\Model;

use App\Utils\Andresmei\FlashResponse;
use App\Entity\Boleto;

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
        switch ($entity) {
            case 'App\Entity\Boleto':
                return $this->createBoletoRegistry($data);
                break;
            default:
                break;
        }
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
            $date = str_replace('/', '-', $data['boletoVencimento']);
            $entity->setBoletoVencimento(new \DateTime($date));
            $entity->setActive(true);

            $this->em->persist($entity);
            $this->em->flush();
            $this->em->getConnection()->commit();

            $httpCode = 200;
            $type = 'success';
            $message = sprintf('Peido de %s numero %d/%d criado com sucesso!', $data['boletoCustomerOwner'], $data['boletoNumber'], $data['boletoInstallment']);
        } catch (\PDOException $e) {
            $this->em->getConnection()->rollback();
            $httpCode = 301;
            $type = 'danger';
            $message = 'Peido não foi criado.';
        }

        return new FlashResponse($httpCode, $type, $message);
    }
}