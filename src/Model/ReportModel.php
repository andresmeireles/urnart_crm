<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\Boleto;
use App\Entity\ManualOrderReport;
use App\Entity\TravelTruckOrders;
use App\Utils\Andresmei\FileFunctions;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\MyDateTime;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Utils\Andresmei\StdResponse;
use App\Utils\Andresmei\StringConvertions;
use App\Utils\Exceptions\CustomException;
use Symfony\Component\Yaml\Yaml;

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
            foreach (array_values($entityData) as $key => $value) {
                $class = new $entity();
                $methodName = sprintf('set%s', ucfirst($key));
                $reflectFunc = new \ReflectionClass($class);
                $functionParameter = $reflectFunc->getMethod($methodName)->getParameters()[0]->getType();
                $typeForConversion = $functionParameter === null ?
                    'none' :
                    $functionParameter->getName();
                $rightTypeValue = (new StringConvertions())->convertValue($typeForConversion, $value);
                $class->{$methodName}($rightTypeValue);
                $objectManager->persist($class);
            }
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
            $httpCode = 301;
            $type = 'danger';
            $message = 'Peido não foi criado.';
        }

        return new FlashResponse($httpCode, $type, $message);
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
     * @param string|null $startDate
     * @param string|null $endingDate
     * @return StdResponse
     * @throws \Exception
     */
    public function generateBoletoPieChart(?string $startDate, ?string $endingDate): StdResponse
    {
        $pastReportRegister = $this->getReportFile(__DIR__ . '/../Utils/ReportFile', $endingDate);
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
        if ($pastReportRegister !== null) {
            foreach ($pastReportRegister as $value) {
                $res[] = $value;
            }
        }
        foreach ($res as $r) {
            foreach ($c as $key => $value) {
                $r['id'] === $value['id'] ?
                    $resultData[$key] = $r :
                    $resultData[$key] = $value;
//                if ($r['id'] === $value['id']) {
//                    $resultData[$key] = $r;
//                    continue;
//                }
//                $resultData[$key] = $value;
            }
        }
        foreach ($resultData as $value) {
            $titlesPrices += $value['boletoValue'];
            switch ($value['boletoStatus']) {
                case 0:
                    ++$data[0];
                    break;
                case 1: //pago
                    ++$data[1];
                    $payedValue += $value['boletoValue'];
                    break;
                case 2:
                    ++$data[2];
                    break;
                case 3:
                    ++$data[3];
                    break;
                case 4:
                    ++$data[4];
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

    /**
     * @param string|null $startDate
     * @param string|null $endingDate
     * @return StdResponse
     * @throws \Exception
     */
    public function generateBoletoListReport(?string $startDate, ?string $endingDate): StdResponse
    {
        $pastReportFile = $this->getReportFile(__DIR__ . '/../Utils/ReportFile', $endingDate);
        $reportResults = $this->searchByDate(
            'Boleto',
            'boletoVencimento',
            'u.boletoCustomerOwner, u.boletoStatus, u.boletoValue, u.boletoVencimento, u.boletoPaymentDate',
            $startDate,
            $endingDate
        );
        $c = $reportResults;
        $pastResponse = [];
        $totalValue = 0;
        $paymentValue = 0;
        $data = [];
        if ($pastReportFile !== null) {
            foreach ($reportResults as $key => $value) {
                $pastResponse[] = $value;
            }
        }
        foreach ($pastResponse as $r) {
            foreach ($c as $key => $value) {
                $r['id'] === $value['id'] ?
                    $reportResults[$key] = $r :
                    $reportResults[$key] = $value;
//                if ($r['id'] === $value['id']) {
//                    $reportResults[$key] = $r;
//                    continue;
//                }
//                $reportResults[$key] = $value;
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
        $response = new StdResponse();
        $response->boletosStatusCount = $data;
        $response->totalValue = $totalValue;
        $response->boletoPayedValue = $paymentValue;

        return $response;
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

        return $this->dqlConsult($consultString);
    }

    /**
     * @param string $intervalBeginDate
     * @param string $intervalLastDate
     * @param string $formatInterval
     * @return array
     * @throws \Exception
     */
    public function getByDateIntervalProductAmount(
        string $intervalBeginDate,
        string $intervalLastDate,
        string $formatInterval = 'Y-m-d'
    ): array {
        $productionDay = '';
        $reportData = [];
        $result = $this->getGenericListByDateArrayFields(
            'ProductionCount',
            'date',
            [],
            $intervalBeginDate,
            $intervalLastDate,
            'date',
            'ASC'
        );
        /** @var ProductionCount $value */
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

    /**
     * @param string $beginDate
     * @param string $lastDate
     * @return StdResponse
     * @throws \Exception
     */
    public function getProductsByModelName(string $beginDate, string $lastDate): StdResponse
    {
        $result = $this->getGenericListByDateArrayFields(
            'ProductionCount',
            'date',
            ['model', 'height', 'amount', 'obs'],
            $beginDate,
            $lastDate
        );
        $height = $this->dqlConsult(
            "SELECT DISTINCT u.height, u.obs FROM App\Entity\ProductionCount u ORDER BY u.obs ASC"
        );
        $model = $this->dqlConsult(
            "SELECT DISTINCT u.model FROM App\Entity\ProductionCount u ORDER BY u.model ASC"
        );
        $response = new StdResponse();
        $response->result = $result;
        $response->height = $height;
        $response->model = $model;

        return $response;
    }

    /**
     * @param string $date
     * @return array
     * @throws CustomException
     */
    public function makeDailyProductionCount(string $date): array
    {
        $reportDate = $date;
        $todayTotal = 0;
        $yesterdayTotal = 0;
        if ($reportDate === '') {
            throw new CustomException('Não é possível criar relátorio sem datas');
        }
        $explodedDate = explode('-', $reportDate);
        $monthBegin = sprintf('%s-%s-%s', '01', $explodedDate[1], $explodedDate[2]);
        $yesterday = sprintf('%s-%s-%s', ((int) $explodedDate[0]) - 1, $explodedDate[1], $explodedDate[2]);
        if (strtolower((new \DateTime($yesterday))->format('l')) === 'sunday') {
            $yesterday = sprintf('%s-%s-%s', ((int) $explodedDate[0]) - 3, $explodedDate[1], $explodedDate[2]);
        }
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
            'yesterdayVal' => $yesterdayTotal,
        ];
    }

    /**
     * @param string $type
     * @param string $dateOne
     * @param string $dateTwo
     * @return StdResponse Ira retornar os valores [template] e [result]
     * @throws CustomException
     */
    public function makeReportByType(string $type, string $dateOne, string $dateTwo): StdResponse
    {
        $explodedDate = explode('-', $dateOne);
        $explodedDateTwo = explode('-', $dateTwo);
        $startDate = sprintf('%s-%s-%s', $explodedDate[0], $explodedDate[1], $explodedDate[2]);
        $lastDate = sprintf('%s-%s-%s', $explodedDateTwo[0], $explodedDateTwo[1], $explodedDateTwo[2]);
        $resultRepo = [];
        $resultRepo['total'] = 0;
        switch ($type) {
            case 'model':
                $typeName = 'm';
                break;
            case 'height':
                $typeName = 'h';
                break;
            default:
                throw new CustomException('Tipo de modelo não funciona.');
                break;
        }
        $report = $this->getGenericListByDateArrayFields(
            'ProductionCount',
            'date',
            [],
            $startDate,
            $lastDate
        );
        /** @var ProductionCount $r */
        if ($typeName === 'm') {
            foreach ($report as $r) {
                $resultRepo['total'] += $r->getAmount();
                array_key_exists($r->getModel(), $resultRepo) ?
                    $resultRepo[$r->getModel()] += $r->getAmount() :
                    $resultRepo[$r->getModel()] = $r->getAmount();
            }
        }
        if ($typeName === 'h') {
            foreach ($report as $r) {
                $resultRepo['total'] += $r->getAmount();
                array_key_exists($r->getHeight(), $resultRepo) ?
                    $resultRepo[$r->getHeight()] += $r->getAmount() :
                    $resultRepo[$r->getModel()] = $r->getAmount();
            }
        }
        $response = new StdResponse();
        $response->template = sprintf('%sTemplate', $type);
        $response->result = $resultRepo;

        return $response;
    }

    /**
     * @param string $dateOne
     * @param string $dateTwo
     * @param string ...$fields
     * @return array
     */
    public function dumpFields(string $dateOne, string $dateTwo, string ...$fields): array
    {
        $explodedDate = explode('-', $dateOne);
        $explodedDateTwo = explode('-', $dateTwo);
        $startDate = sprintf('%s-%s-%s', $explodedDate[0], $explodedDate[1], $explodedDate[2]);
        $lastDate = sprintf('%s-%s-%s', $explodedDateTwo[0], $explodedDateTwo[1], $explodedDateTwo[2]);
        $results = [];
        foreach ($fields as $field) {
            $query = sprintf(
                'SELECT DISTINCT(u.%s) FROM App\Entity\ProductionCount u WHERE u.date BETWEEN %s AND %s',
                $field,
                $startDate,
                $lastDate
            );
            $result = $this->dqlConsult($query);
            $results[] = $result;
        }

        return $results;
    }

    /**
     * @param array $reportData Mandar parametro com as sefintes informaçãoes no devido formato
     *                          'driverName' => 'Nome do rapaz',
     *                          'kmout' => null|'236599',
     *                          'departureDate => null|'2010/10/31' Formarto YYYY/MM/DD
     * @param array $orders Array associativo contendo ids de ManualOrderReport
     * @return FlashResponse
     * @throws \Exception
     */
    public function createTruckDepartureReport(array $reportData, array $orders): FlashResponse
    {
        $entityManager = $this->entityManager;
        $manualOrderRepository = $entityManager->getRepository(ManualOrderReport::class);
        $truckArrivalReport = new TravelTruckOrders();
        $simpleArray = [];
        try {
            $truckArrivalReport->setDriverName($reportData['driverName']);
            $truckArrivalReport->setKmout($reportData['kmout']);
            $date = new MyDateTime($reportData['departureDate'], 'America/Belem');
            $truckArrivalReport->setDepartureDate($date);
            foreach ($orders as $order) {
                $simpleArray[$order['id']] = isset($order['isChecked']) ? (bool) $order['isChecked'] : false;
                $manualOrderReport = $manualOrderRepository->find($order['id']);
                $truckArrivalReport->addOrderId($manualOrderReport);
            }
            $truckArrivalReport->setCheckedOrders($simpleArray);
            $truckArrivalReport->setActive(true);
            $entityManager->persist($truckArrivalReport);
        } catch (\Exception $error) {
            throw new \Exception($error->getMessage(), $error->getCode());
        }
        $entityManager->flush();

        return new FlashResponse(200, 'success', 'Relatorio de saida do caminhão criado com sucesso!');
    }

    /**
     * @param TravelTruckOrders $orderTruckEntity
     * @param array $reportData
     * @param array $orders
     * @return FlashResponse
     * @throws \Exception
     */
    public function editTruckDepartureReport(TravelTruckOrders $orderTruckEntity, array $reportData, array $orders): FlashResponse
    {
        $entityManager = $this->entityManager;
        $manualOrderRepository = $entityManager->getRepository(ManualOrderReport::class);
        /** @var TravelTruckOrders $truckReport */
        $truckReport = $entityManager->getRepository(TravelTruckOrders::class)->find($orderTruckEntity->getId());
        try {
            $truckReport->setDriverName($reportData['driverName']);
            $truckReport->setKmout($reportData['kmout']);
            $date = new MyDateTime($reportData['departureDate'], 'America/Belem');
            $truckReport->setDepartureDate($date);
            $truckReport->getOrderId()->map(static function ($value) use ($truckReport) {
                $truckReport->removeOrderId($value);
            });
            foreach ($orders as $order) {
                $simpleArray = [$order['id'] => isset($order['isChecked']) ? (bool) $order['isChecked'] : false];
                $manualOrderReport = $manualOrderRepository->find($order['id']);
                $truckReport->addOrderId($manualOrderReport);
            }
            $truckReport->setCheckedOrders($simpleArray ?? []);
            $entityManager->merge($truckReport);
        } catch (\Exception $error) {
            throw new \Exception(
                sprintf(
                    '%s, no arquivo %s e linha %s',
                    $error->getMessage(),
                    $error->getFile(),
                    $error->getLine()
                ),
                $error->getCode()
            );
        }
        $entityManager->flush();

        return new FlashResponse(
            200,
            'success',
            sprintf('Relatorio %s editado com sucesso', $orderTruckEntity->getId())
        );
    }

    /**
     * @param string $path
     * @param string|null $date
     * @return array|null
     */
    private function getReportFile(string $path, ?string $date): ?array
    {
        $response = null;
        $reportName = $date === null ?
            (new FileFunctions())->getLastCreateFileFromFolder($path) :
            (new FileFunctions())->getFileByDate($path, $date);
        if ($reportName !== null && file_exists($reportName)) {
            $dataFile = file_get_contents($reportName);
            $response = Yaml::parse((string) $dataFile);
        }

        return $response;
    }
}
