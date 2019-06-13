<?php declare(strict_types=1);

namespace App\Controller;

use App\Model\ReportModel;
use App\Entity\ManualOrderReport;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Entity\TravelTruckOrders;

class TruckController extends AbstractController
{
    /**
     * @Route("/truck", name="truck.index")
     */
    public function index(): Response
    {
        $reports = $this->getDoctrine()->getRepository(TravelTruckOrders::class)->findAll();

        return $this->render('truck/truck.html.twig', [
            'reports' => $reports
        ]);
    }

    /**
     * @Route("truck/create", methods="GET", name="create.truck.report")
     */
    public function viewCreateForm(): Response
    {
        /** @var ManualOrderReportRepository|ManualOrderReport */
        $ordersId = $this->getDoctrine()->getRepository(ManualOrderReport::class)->someFieldsConsult('id', 'customerName');
        
        return $this->render('truck/pages/createTruckRepo.html.twig', [
            'orders' => $ordersId
        ]);
    }

    /**
     * @Route("truck/create", methods="POST")
     */
    public function createTruckForm(Request $request, ReportModel $reportModel): Response
    {
        $alldata = $request->request->all();
        $nestedArray = new NestedArraySeparator($alldata);
        $reportInformation = $nestedArray->getSimpleArray();
        $reportOrderInformation = $nestedArray->getArrayInArray();
        $result = $reportModel->createTruckDepartureReport($reportInformation, $reportOrderInformation);
        $this->addFlash(
            $result->getType(),
            $result->getMessage()
        );

        return $this->redirectToRoute('truck.index');
    }

    /**
     * @Route("truck/edit/{id<\d+>}", methods="GET")
     */
    public function viewEditForm(int $id): Response
    {
        /** @var TravelTruckOrders $data */
        $data = $this->getDoctrine()->getRepository(TravelTruckOrders::class)->find($id);
        $ordersId = $this->getDoctrine()->getRepository(ManualOrderReport::class)->someFieldsConsult('id', 'customerName');
        
        return $this->render('truck/pages/editTruckRepo.html.twig', [
            'orders' => $ordersId,
            'data' => $data
        ]);
    }

    /**
     * @Route("truck/edit/{id<\d+>}", methods="POST")
     */
    public function editTruckForm(Request $request, ReportModel $reportModel, int $id): Response
    {
        $alldata = $request->request->all();
        $nestedArray = new NestedArraySeparator($alldata);
        $reportInformation = $nestedArray->getSimpleArray();
        $reportOrderInformation = $nestedArray->getArrayInArray();
        $truckOrderEntity = $this->getDoctrine()->getRepository(TravelTruckOrders::class)->find($id);
        $result = $reportModel->editTruckDepartureReport($truckOrderEntity, $reportInformation, $reportOrderInformation);
        $this->addFlash(
            $result->getType(),
            $result->getMessage()
        );

        return $this->redirectToRoute('truck.index');
    }
}
