<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\ModelName;
use App\Entity\ProductionCount;
use App\Repository\ProductionCountRepository;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\DateFunctions;
use App\Utils\Andresmei\StdResponse;
use App\Utils\Exceptions\CustomException;
use Symfony\Component\HttpFoundation\ParameterBag;

final class ProductionCountModel extends Model
{
    /**
     * @param array $productionInformation
     * @return FlashResponse
     * @throws \Exception
     */
    public function createProductionCount(array $productionInformation): FlashResponse
    {
        $entityManager = $this->entityManager;
        $modelRepository = $entityManager->getRepository(ModelName::class);
        try {
            foreach ($productionInformation as $productionItem) {
                $production = new ProductionCount();
                $idAndColor = explode('-', $productionItem['model']);
                /** @var ModelName $modelItem */
                $modelItem = $modelRepository->find($idAndColor[0]);
                $production->setModel($modelItem->getName());
                $production->setHeight($modelItem->getHeight());
                $production->setObs(
                    $modelItem->getSpecificity() === '' ?
                    null :
                    $modelItem->getSpecificity()
                );
                $production->setAmount((int) $productionItem['amount']);
                $production->setDate($productionItem['date']);
                $production->setColor($idAndColor[1] ?? null);
                $production->setActive(true);
                $entityManager->persist($production);
            }
            $entityManager->flush();
        } catch (\Exception $error) {
            throw new \Exception($error->getMessage());
        }

        return new FlashResponse(200, 'success', 'Produto inserido com sucesso');
    }

    /**
    * @param string $date
    * @return array
    * @throws CustomException
    */
   public function makeDailyProductionCouterReport(string $date, ProductionCountRepository $repository): array
   {
       $dateTime = new \DateTime($date);
       $today = $this->changeIfWeekend($dateTime);
       $yesterday = clone $dateTime;
       $yesterday = $this->changeIfWeekend($yesterday->modify('-1 day'));
       $todayProduction = $repository->getMonthProductionByDate($today);
       $yesterdayProduction = $repository->getMonthProductionByDate($yesterday);

       return [
           'reportDay' => $dateTime,
           'today' => $todayProduction['valor'],
           'yesterdayDay' => $yesterday,
           'yesterday' => $yesterdayProduction['valor']
       ];
   }

   private function changeIfWeekend(\DateTime $date): \Datetime
   {
       if ($date->format('w') === '0') {
           return $this->changeIfWeekend($date->modify('-1 day'));
       }

       return $date;
   }

    /**
     * @param string $date
     * @return array
     * @throws CustomException
     */
    // public function makeDailyProductionCount(string $date): array
    // {
    //     $reportDate = $date;
    //     $todayTotal = 0;
    //     $yesterdayTotal = 0;
    //     if ($reportDate === '') {
    //         throw new CustomException('Não é possível criar relátorio sem datas');
    //     }
    //     $explodedDate = explode('-', $reportDate);
    //     $monthBegin = sprintf('%s-%s-%s', '01', $explodedDate[1], $explodedDate[2]);
    //     $yesterday = sprintf('%s-%s-%s', ((int) $explodedDate[0]) - 1, $explodedDate[1], $explodedDate[2]);
    //     if (strtolower((new \DateTime($yesterday))->format('l')) === 'sunday') {
    //         $yesterday = sprintf('%s-%s-%s', ((int) $explodedDate[0]) - 3, $explodedDate[1], $explodedDate[2]);
    //     }
    //     dump(new DateTime($reportDate), (new DateTime($reportDate))->modify('+1 day'), (new DateTime($reportDate))->modify('-1 day')); die;
    //     $this->repository->getProductionInRageDate($monthBegin, $reportDate);
    //     $todayReport = $this->getByDateIntervalProductAmount($monthBegin, $reportDate);
    //     $yesterdayReport = $this->getByDateIntervalProductAmount($monthBegin, $yesterday);

    //     foreach ($todayReport as $tr) {
    //         /** @var ProductionCount $value */
    //         foreach ($tr as $value) {
    //             $todayTotal += $value->getAmount();
    //         }
    //     }

    //     foreach ($yesterdayReport as $tr) {
    //         /** @var \App\Entity\ProductionCount $value */
    //         foreach ($tr as $value) {
    //             $yesterdayTotal += $value->getAmount();
    //         }
    //     }

    //     return [
    //         'today' => $reportDate,
    //         'todayVal' => $todayTotal,
    //         'yesterday' => $yesterday,
    //         'yesterdayVal' => $yesterdayTotal,
    //     ];
    // }


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
        $height = $this->dqlQuery(
            "SELECT DISTINCT u.height, u.obs FROM App\Entity\ProductionCount u ORDER BY u.obs ASC"
        );
        $model = $this->dqlQuery(
            "SELECT DISTINCT u.model FROM App\Entity\ProductionCount u ORDER BY u.model ASC"
        );
        $response = new StdResponse();
        $response->result = $result;
        $response->height = $height;
        $response->model = $model;

        return $response;
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
     * @param \DateTimeInterface $beginDate
     * @param \DateTimeInterface $endingDate
     * @return iterable
     */
    public function getRegistersByDates(\DateTimeInterface $beginDate, \DateTimeInterface $endingDate): iterable
    {
        $dqlQuery = sprintf(
            "SELECT u.model, u.height, u.amount, u.obs FROM %s u WHERE u.date > '%s' AND u.date < '%s'",
            ProductionCount::class,
            $beginDate->format('Y-m-d'). ' 23:00:00',
            $endingDate->format('Y-m-d') . ' 00:00:00'
        );
        $products = $this->dqlQuery($dqlQuery);
        array_map(static function ($prod) use (&$formattedProducts) {
            $name = sprintf(
                '%s %s %s',
                $prod['model'],
                $prod['height'],
                $prod['obs'] ?? ''
            );
            $formattedProducts[$name] = $prod['amount'];
        }, $products);

        return $formattedProducts;
    }

    public function getProductSequenceTotalPrice(iterable $productSequence, iterable $productsNameAndPrice)
    {
        $totalPriceAmount = 0;
        foreach ($productSequence as $productName => $amount) {
            if (array_key_exists($productName, (array) $productsNameAndPrice)) {
                $totalPriceAmount += $productsNameAndPrice[$productName]['price'] * $amount;
                continue;
            }
        }

        return $totalPriceAmount;
    }

    /**
     * @param ParameterBag $request
     * @return array
     */
    public function getProductionCountData(ParameterBag $request): array
    {
        $requestInformation = [];
        if ($request->get('month') !== null) {
            return $this->getInfoByMonthYear($request->get('month'));
        }
        $requestInformation['beginDate'] = $request->get('begin-date');
        $requestInformation['endDate'] = $request->get('last-date');

        return $requestInformation;
    }

    /**
     * @param string $monthYear
     * @return array
     */
    private function getInfoByMonthYear(string $monthYear): array
    {
        $monthYear = explode('-', $monthYear);
        $requestInformation['month'] = $monthYear[0];
        $requestInformation['year'] = $monthYear[1];
        $lastMonthDay = DateFunctions::getLastDayOfMonth($monthYear[0]);
        $requestInformation['beginDate'] = sprintf(
            '%s-%s-%s',
            01,
            $requestInformation['month'],
            $requestInformation['year']
        );
        $requestInformation['endDate'] = sprintf(
            '%s-%s-%s',
            $lastMonthDay,
            $requestInformation['month'],
            $requestInformation['year']
        );

        return $requestInformation;
    }


}
