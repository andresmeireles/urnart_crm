<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\ProductionCount;
use App\Model\ModelsModel;
use App\Model\ProductionCountModel;
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
        ModelsModel $nameModel
    ): Response {
        $requestInfo = $model->getProductionCountData($request->request);
        $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'productionCountRepo');
        $productionCountRepository = $this->getDoctrine()->getRepository(ProductionCount::class);
        $productNamesAndPrices = $nameModel->getModelPrices();
        $beginDate = new \DateTime($requestInfo['beginDate']);
        $endingDate = new \DateTime($requestInfo['endDate']);
        $productNames = $productionCountRepository->getDistinctProductAmountsByDate($beginDate, $endingDate);
        $totalAmountByDate = $productionCountRepository->getAmountByDates($beginDate, $endingDate);
        $totalProductValues = $model->getProductSequenceTotalPrice($productNames, $productNamesAndPrices);
        $listOfModels = $productionCountRepository->findInTimeInterval(['model'], $beginDate, $endingDate);
        $listOfModels = array_map(static function ($prod) { return $prod[1]; } , $listOfModels);
        $listOfHeights = $productionCountRepository->getProductHeightsListByDate($beginDate, $endingDate);

        return $this->render('print/report/monthBalance.html.twig', [
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
}
