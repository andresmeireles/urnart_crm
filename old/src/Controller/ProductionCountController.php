<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\ProductionCount;
use App\Model\ModelsModel;
use App\Model\ProductionCountModel;
use App\Repository\ProductionCountRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductionCountController extends AbstractController
{
    use CSRFTokenCheck;

    /**
     * @Route("/production/count/report/bydate", methods={"POST"},name="prod_count_by_date")
     */
    public function createProductionCountReportByDate(
        Request $request,
        ProductionCountModel $model,
        ProductionCountRepository $productionCountRepository,
        ModelsModel $nameModel
    ): Response {
        $requestInfo = $model->getProductionCountData($request->request);
        $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'productionCountRepo');
        $productNamesAndPrices = $nameModel->getModelPrices();
        $beginDate = new \DateTime($requestInfo['beginDate']);
        $endingDate = new \DateTime($requestInfo['endDate']);

        $productNames = $productionCountRepository->getDistinctProductAmountsByDate($beginDate, $endingDate);
        $totalAmountByDate = $productionCountRepository->getAmountByDateRange($beginDate, $endingDate);
        $totalProductValues = $model->getProductSequenceTotalPrice($productNames, $productNamesAndPrices);
        $listOfModels = $productionCountRepository->findInTimeInterval(['model'], $beginDate, $endingDate);
        $listOfModels = array_map(static function ($prod) { return $prod[1]; } , $listOfModels);
        $listOfHeights = $productionCountRepository->getProductHeightsListByDate($beginDate, $endingDate);

        return $this->render('print/report/dataRangeBalance.html.twig', [
            'bDate' => $beginDate,
            'lDate' => $endingDate,
            'heights' => $listOfHeights,
            'model' => $listOfModels,
            'products' => $productNames,
            'finalTotal' => $totalProductValues,
            'totalAmountProducts' => $totalAmountByDate,
            'isMonth' => (bool) $request->request->get('isMonth'),
            'month' => $requestInfo['month'] ?? '',
            'year' => $requestInfo['year'] ?? ''
        ]);
    }

    /**
    * @Route("/production/count/report/month", name="month_report", methods="POST")
    */
    public function buildMonthReport(
        Request $request,
        ProductionCountRepository $repository,
        ProductionCountModel $model,
        ModelsModel $nameModel
    ): Response {
        $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'productionCountRepo');
        $date = $request->request->get('month');
        $month = (int) strstr($date, '-', true);
        $year = (int) str_replace('-', '', strstr($date, '-'));
        $reportQuery = $repository->getProductionReportByMonth($month, $year);
        
        return $this->render('print/report/monthBalance.html.twig', [
            'month' => $month,
            'heights' => $reportQuery['height'],
            'model' => $reportQuery['model'],
            'products' => $reportQuery['products'],
            'finalTotal' => $model->getProductSequenceTotalPrice($reportQuery['products'], $nameModel->getModelPrices()),
            'totalAmountProducts' => $reportQuery['amount'],
            'year' => $year
        ]);
    }
}
