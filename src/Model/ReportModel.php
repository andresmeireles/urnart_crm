<?php declare(strict_types=1);

namespace App\Model;

use App\Utils\Andresmei\FlashResponse;
use App\Entity\Boleto;
use App\Utils\Andresmei\StdResponse;
use Symfony\Component\Yaml\Yaml;
use App\Utils\Andresmei\FileFunctions;
use App\Utils\Exceptions\CustomException;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Utils\Andresmei\StringConvertions;

class ReportModel extends Model
{
    /**
     * Select the create function and do the action.
     *
     * @param string $entity
     * @param array  $data
     * @return FlashResponse
     */
    public function createGenericReport(string $entity, array $data): FlashResponse
    {
        $flashResult = '';

        switch ($entity) {
            case 'boleto':
                $flashResult = $this->createBoletoRegistry($data);
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

    public function createGenericRegistryArray(string $entityName, array $data): FlashResponse
    {
        $entity = sprintf('App\Entity\%s', ucfirst($entityName));
        $em = $this->em;
        $info = new NestedArraySeparator($data);
        $entityData = $info->getArrayInArray();
        foreach ($entityData as $data) {
            $class = new $entity;
            foreach ($data as $key => $value) {
                $methodName = sprintf('set%s', ucfirst($key));
                
                $reflectFunc = new \ReflectionClass($class);
                $classReflec = $reflectFunc->getMethod($methodName)->getParameters()[0]->getType();
                
                if ($classReflec !== null) {
                    $type = $classReflec->getName();
                    $value = (new StringConvertions())->convertValue($type, $value);
                }

                $class->$methodName($value);
            }
            $em->persist($class);
            $em->flush();
        }

        return new FlashResponse(200, 'success', 'Ação concluida com sucesso.');
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
            $message = sprintf(
                'Titulo de %s numero %s/%d criado com sucesso!',
                $data['boletoCustomerOwner'],
                $data['boletoNumber'],
                $data['boletoInstallment']
            );
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

                if (strpos($value, '.') !== false) {
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

        return new FlashResponse(200, 'success', sprintf(
            'Titulo %s/%s atualizado com sucesso.',
            $entityToEdit->getBoletoNumber(),
            $entityToEdit->getBoletoInstallment()
        ));
    }
    
    public function getGenericList(string $entity, string $typeOfOrder): StdResponse
    {
        $order = '';
        $list = '';
        
        switch ($typeOfOrder) {
            case 'last':
                $list = 'Ultimos Títulos';
                $order = array('createDate' => 'ASC');
                break;
            case 'client':
                $list = 'Títulos por cliente';
                if ($entity === 'boleto') {
                    $order = array('boletoCustomerOwner' => 'ASC');
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

        $returnObject = new StdResponse;
        $returnObject->typeOfList = $list;
        $returnObject->consultResults = $this->em->getRepository($entity)->findBy(array(), $order);

        return $returnObject;
    }

    /**
     * Return a serializaed register from database.
     *
     * @param string $entity    Entidade para consultar.
     * @param int    $consultId Id to consult registry.
     *
     * @return string
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

                $porContaArray[] = array(
                    'statusDate' => (new \DateTime('now'))->format('d/m/Y'),
                    'porContaValue' => $boletoData['porContaValue'],
                    'porContaDate' => $boletoData['porContaDate']
                );

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
     * Lista por data dados de um repositorio.
     *
     * @param   string|null $startDate
     * @param   string|null $endingDate
     * @return  StdResponse
     */
    public function generateBoletoPieChart(?string $startDate, ?string $endingDate): StdResponse
    {
        $pastReportRegister = $this->getReportFile(__DIR__.'/../Utils/ReportFile', $endingDate);

        $resultData = $this->getGenericListByDate(
            'Boleto',
            'boletoVencimento',
            'u.boletoStatus, u.id,u.boletoValue',
            $startDate,
            $endingDate
        );
        $c = $resultData;
        //$data = [0 => 0, 1 => 0, 2 => 0, 3 => 0, 4 => 0];
        $data = [0, 0, 0, 0, 0];
        $statusNames = ['Não pago', 'Pago', 'Pgto. Atrasado', 'Pgto. Provisionado', 'Pgto. por Conta'];
        $titlesPrices = 0;
        $payedValue = 0;
        $res = [];

        if (!is_null($pastReportRegister)) {
            foreach ($pastReportRegister as $key => $value) {
                foreach ($value as $k => $v) {
                    $res[] = $v;
                }
            }
        }

        foreach ($res as $r) {
            foreach ($c as $key => $value) {
                if ($r['id'] === $value['id']) {
                    $resultData[$key] = $r;
                    continue;
                }
                $resultData[$key] = $value;
            }
        }

        foreach ($resultData as $key => $value) {
            $titlesPrices += $value['boletoValue'];
            switch ($value['boletoStatus']) {
                case 0:
                    $data[0] += 1;
                    break;
                case 1: //pago
                    $data[1] += 1;
                    $payedValue += $value['boletoValue'];
                    break;
                case 2:
                    $data[2] += 1;
                    break;
                case 3:
                    $data[3] += 1;
                    break;
                case 4:
                    $data[4] += 1;
                    break;
                default:
                    throw new \Exception('Tem algo muito errado.');
            }
        }

        $response = new StdResponse();
        $response->boletosStatusCount = $data;
        $response->statusNames = $statusNames;
        $response->boletoPayedValue = $payedValue;
        $response->totalValue = $titlesPrices;

        return $response;
    }

    /**
     * Return required fields from databse by start and ending date, if has.
     *
     * @param   string       $entity          Entity for search.
     * @param   string       $whereField      fields to WHERE condition.
     * @param   string|null  $requiredFields  Fields required to return.
     * @param   string|null  $startDate       Start consult date.
     * @param   string|null  $endingDate      Ending cosult date.
     *
     * @return  array                         Array with results of consult.
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

    public function generateBoletoListReport(?string $startDate, ?string $endingDate): StdResponse
    {
        $pastReportFile = $this->getReportFile(__DIR__.'/../Utils/ReportFile', $endingDate);

        $reportResults = $this->searchByDate(
            'Boleto',
            'boletoVencimento',
            'u.boletoCustomerOwner, u.boletoStatus, u.boletoValue, u.boletoVencimento, u.boletoPaymentDate',
            $startDate,
            $endingDate
        );
        $c = $reportResults;
        $pastResponse = array();
        $totalValue = 0;
        $paymentValue = 0;
        $data = [];

        if (!is_null($pastReportFile)) {
            foreach ($reportResults as $key => $value) {
                foreach ($value as $k => $v) {
                    $pastResponse[] = $v;
                }
            }
        }

        foreach ($pastResponse as $r) {
            foreach ($c as $key => $value) {
                if ($r['id'] === $value['id']) {
                    $reportResults[$key] = $r;
                    continue;
                }
                $reportResults[$key] = $value;
            }
        }

        foreach ($reportResults as $key => $value) {
            $totalValue += $value['boletoValue'];
            switch ($value['boletoStatus']) {
                case 0:
                    $data['Atrasado'][] = $reportResults[$key];
                    break;
                case 1: //pago
                    $data['Pago'][] = $reportResults[$key];
                    $paymentValue += $value['boletoValue'];
                    break;
                case 2:
                    $data['Pgto. Atrasado'][] = $reportResults[$key];
                    break;
                case 3:
                    $data['Pgto. Provisionado'][] = $reportResults[$key];
                    break;
                case 4:
                    $data['Pgto. Por Conta'][] = $reportResults[$key];
                    break;
                default:
                    throw new \Exception('Tem algo muito errado.');
            }
        }

        $response = new StdResponse;
        $response->boletosStatusCount = $data;
        $response->totalValue = $totalValue;
        $response->boletoPayedValue = $paymentValue;
        return $response;
    }

    public function getNonPayedBoletosByDate(string $date): array
    {
        $consultString = sprintf(
            'SELECT u FROM App\Entity\Boleto u WHERE u.boletoVencimento <  %s AND u.boletoStatus <> 1',
            "'{$date} %'"
        );
        $result = $this->dqlConsult($consultString);
        return $result;
    }

    private function getReportFile(string $path, ?string $date): ?array
    {
        $response = null;

        $reportName = empty($date) ?
            (new FileFunctions)->getLastCreateFileFromFolder($path) :
            (new FileFunctions)->getFileByDate($path, $date);

        if (!is_null($reportName) && file_exists($reportName)) {
            $dataFile = file_get_contents($reportName);
            $response = Yaml::parse((string) $dataFile);
        }

        return $response;
    }

    public function getByDateIntervalProductAmount(
        string $intervalBeginDate,
        string $intervalLastDate,
        string $formatInterval = 'Y-m-d'
    ): array {
        $productionDay = '';
        $reportData = array();

        $result = $this->getGenericListByDateArrayFields(
            'ProductionCount',
            'date',
            array(),
            $intervalBeginDate,
            $intervalLastDate,
            'date',
            'ASC'
        );
        /** @var \App\Entity\ProductionCount $value */
        foreach ($result as $value) {
            if ($value->getDate() === null) {
                throw new \Exception('Algo de errado não está certo. Não deveria haver um date null.');
            }
            
            $actualDate = $value->getDate()->format($formatInterval);

            if ($actualDate !== $productionDay) {
                $productionDay = $actualDate;
                $reportData[$actualDate][] = $value;
                continue;
            }

            if ($actualDate === $productionDay) {
                $reportData[$actualDate][] = $value;
            }
        }

        return $reportData;
    }

    public function getProductsByModelName(string $beginDate, string $lastDate): array
    {
        $result = $this->getGenericListByDateArrayFields(
            'ProductionCount',
            'date',
            ['name', 'amount'],
            $beginDate,
            $lastDate
        );

        foreach ($result as $key => $field) {
            $nameModel = explode(' ', $field['name']);
            $result[$key]['model'] = $nameModel[0];
            $result[$key]['height'] = $nameModel[1];
        }

        dump($result);
        die();
    }

    public function makeDailyProductionCount(string $date): array
    {
        $reportDate = $date;
        $todayTotal = 0;
        $yesterdayTotal = 0;
        if ('' === $reportDate) {
            throw new CustomException('Não é possível criar relátorio sem datas');
        }
        $explodedDate = explode('-', $reportDate);
        $monthBegin = sprintf("%s-%s-%s", '01', $explodedDate[1], $explodedDate[2]);
        $yesterday = sprintf("%s-%s-%s", ( (int) $explodedDate[0]) - 1, $explodedDate[1], $explodedDate[2]);
        $todayReport = $this->getByDateIntervalProductAmount($monthBegin, $reportDate);
        $yesterdayReport = $this->getByDateIntervalProductAmount($monthBegin, $yesterday);

        foreach ($todayReport as $tr) {
            /** @var \App\Entity\ProductionCount $value */
            foreach ($tr as $value) {
                $todayTotal += $value->getAmount();
            }
        }

        foreach ($yesterdayReport as $tr) {
            /** @var \App\Entity\ProductionCount $value */
            foreach ($tr as $value) {
                $yesterdayTotal += $value->getAmount();
            }
        }

        return [
            'today' => $reportDate,
            'todayVal' => $todayTotal,
            'yesterday' => $yesterday,
            'yesterdayVal' => $yesterdayTotal
        ];
    }
}
