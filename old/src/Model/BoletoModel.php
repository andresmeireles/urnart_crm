<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\Boleto;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\StdResponse;
use App\Utils\Andresmei\WriteBoletoReport;
use App\Utils\Exceptions\CustomException;

/**
 * Class BoletoModel
 * @package App\Model
 */
final class BoletoModel extends Model
{
    /**
     * @param array $data
     * @return FlashResponse
     * @throws \PDOException
     */
    public function createBoletoRegistry(array $data): FlashResponse
    {
        $entity = new Boleto();
        try {
            $entity->setBoletoCustomerOwner($data['boletoCustomerOwner']);
            $entity->setBoletoNumber($data['boletoNumber']);
            $entity->setBoletoInstallment($data['boletoInstallment']);
            $entity->setBoletoValue($data['boletoValue']);
            $entity->setBoletoVencimento($data['boletoVencimento']);
            $entity->setActive(true);

            $this->entityManager->persist($entity);
            $this->entityManager->flush();

            $httpCode = 200;
            $type = 'success';
            $message = sprintf(
                'Titulo de %s numero %s/%d criado com sucesso!',
                $data['boletoCustomerOwner'],
                $data['boletoNumber'],
                $data['boletoInstallment']
            );
        } catch (\PDOException $e) {
            $httpCode = 200;
            $type = 'danger';
            $message = 'Peido não foi criado.';
        }

        return new FlashResponse($httpCode, $type, $message);
    }

    /**
     * @param int $boletoId    Identificaction of Boleto entity
     * @param array $boletoData  Status and date info.
     * @return FlashResponse
     * @throws CustomException|\PDOException
     */
    public function boletoChangeStatus(int $boletoId, array $boletoData): FlashResponse
    {
        $entityManager = $this->entityManager;
        $boletoRegistry = $entityManager->getRepository(Boleto::class)->find($boletoId);
        if ($boletoRegistry === null) {
            throw new \PDOException('Não é uma instancia de Boleto entity.');
        }
        try {
            $status = (int) $boletoData['boletoStatus'];
            $boletoRegistry->setBoletoStatus($status);
            if ($status === 1 || $status === 2) {
                $boletoRegistry->setBoletoPaymentDate($boletoData['boletoPaymentDate']);
                $boletoRegistry->setActive(false);
            }
            if ($status === 3) { //Pagamento Provisionado
                $boletoRegistry->setBoletoProvisionamentoDate($boletoData['boletoProvisionamentoDate']);
            }
            if ($status === 4) {//Pagamento Por Conta
                if ($boletoData['porContaValue'] > $boletoRegistry->getBoletoValue()) {
                    throw new CustomException(sprintf(
                        'Erro no valor da parcela: Valor de R$ %s maior que R$ %s do valor total do título %s/%s',
                        number_format($boletoData['porContaValue'], 2, '.', ','),
                        number_format($boletoRegistry->getBoletoValue(), 2, ',', '.'),
                        $boletoRegistry->getBoletoNumber(),
                        $boletoRegistry->getBoletoInstallment()
                    ));
                }
                $porContaArray = [
                    'statusDate' => (new \DateTime('now'))->format('d/m/Y'),
                    'porContaValue' => $boletoData['porContaValue'],
                    'porContaDate' => $boletoData['porContaDate'],
                ];
                $boletoRegistry->setBoletoPorContaStatus($porContaArray);
            }
            $entityManager->merge($boletoRegistry);
            $entityManager->flush();
        } catch (\PDOException $e) {
            throw new \PDOException(sprintf('Error Processing Request. %s', $e->getMessage()), 1);
        }

        return new FlashResponse(
            200,
            'success',
            sprintf(
                'Titulo %s/%d teve seu staus atualizado com sucesso.',
                $boletoRegistry->getBoletoNumber(),
                $boletoRegistry->getBoletoInstallment()
            )
        );
    }

    /**
     * @param string $date
     * @return array
     */
    public function getNonPayedBoletosByDate(string $date): array
    {
        $consultString = sprintf(
            'SELECT u FROM App\Entity\Boleto u WHERE u.boletoVencimento <  %s AND u.boletoStatus <> 1',
            "'{$date} %'"
        );

        return $this->dqlQuery($consultString);
    }

    /**
     * @param \DateTimeInterface $date
     */
    public function writeDaylyBoletoRegister(
        \DateTimeInterface $date
    ): void {
        $titulos = $this->getNonPayedBoletosByDate($date->format('Y-m-d'));

        $response = new StdResponse();

        foreach ($titulos as $titulo) {
            /** @var Boleto $titulo */
            switch ($titulo->getBoletoStatus()) {
                case 2:
                    $response->atrasado[] = $titulo;
                    break;
                case 3:
                    $response->provisionado[] = $titulo;
                    break;
                case 4:
                    $response->conta[] = $titulo;
                    break;
                default:
                    $response->naoPago[] = $titulo;
                    break;
            }
        }
        $report = new WriteBoletoReport();
        $report->write($date, $response);
    }
}
