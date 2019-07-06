<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\ManualOrderReport;
use App\Entity\TravelTruckOrders;
use App\Model\DepartureModel;
use App\Model\ReportModel;
use App\Utils\Andresmei\Form;
use App\Utils\Andresmei\NestedArraySeparator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class TruckController extends AbstractController
{
    /**
     * @Route("/truck", name="truck.index")
     */
    public function index(): Response
    {
        $reports = $this->getDoctrine()->getRepository(TravelTruckOrders::class)->findAll();

        return $this->render('truck/truck.html.twig', [
            'reports' => $reports,
        ]);
    }

    /**
     * @Route("/truck/create", methods="GET", name="create.truck.report")
     * @method \App\Repository\ManualOrderReportRepository getRepository()
     */
    public function viewCreateForm(): Response
    {
        /** @var \App\Repository\ManualOrderReportRepository $manualRepository */
        $manualRepository = $this->getDoctrine()->getRepository(ManualOrderReport::class);

        return $this->render('truck/pages/createTruckRepo.html.twig', [
            'orders' => $manualRepository->someFieldsConsult('id', 'customerName'),
        ]);
    }

    /**
     * @Route("/truck/create", methods="POST")
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
     * @Route("/truck/edit/{id<\d+>}", methods="GET")
     */
    public function viewEditForm(int $id): Response
    {
        /** @var ManualOrderReportRepository $manualRepository */
        $manualRepository = $this->getDoctrine()->getRepository(ManualOrderReport::class);

        return $this->render('truck/pages/editTruckRepo.html.twig', [
            'orders' => $manualRepository->someFieldsConsult('id', 'customerName'),
            'data' => $this->getDoctrine()->getRepository(TravelTruckOrders::class)->find($id),
        ]);
    }

    /**
     * @Route("/truck/edit/{id<\d+>}", methods="POST")
     */
    public function editTruckForm(Request $request, ReportModel $reportModel, int $id): Response
    {
        $alldata = $request->request->all();
        $nestedArray = new NestedArraySeparator($alldata);
        $reportInformation = $nestedArray->getSimpleArray();
        $reportOrderInformation = $nestedArray->getArrayInArray();
        /** @var TravelTruckOrders $truckOrderEntity */
        $truckOrderEntity = $this->getDoctrine()->getRepository(TravelTruckOrders::class)->find($id);
        $result = $reportModel->editTruckDepartureReport(
            $truckOrderEntity,
            $reportInformation,
            $reportOrderInformation
        );
        $this->addFlash(
            $result->getType(),
            $result->getMessage()
        );

        return $this->redirectToRoute('truck.index');
    }

    /**
     * @Route("/truck/create/report/model/show/{truckReportId<\d+>}")
     */
    public function createModelsNamesReport(int $truckReportId): Response
    {
        return new Response(sprintf('Trablho em andamento. calma que ja vai chegar. Id é %s', $truckReportId));
    }

    /**
     * @Route("/truck/create/report/{typeReport}/show/{entityId<\d+>}")
     */
    public function createSingleReports(
        Form $form,
        DepartureModel $departureModel,
        string $typeReport,
        int $entityId
    ): Response {
        /** @var TravelTruckOrders $entityClass */
        $entityClass = $this->getDoctrine()
            ->getRepository(TravelTruckOrders::class)
            ->find($entityId);
        $result = $departureModel->generateAutomaticShowReportWithData(
            $entityClass,
            $form,
            $typeReport
        );

        return new Response($result);
    }

    /**
     * @Route("/truck/create/report/{typeReport}/pdf/{entityId<\d+>}")
     */
    public function createPdfReport(
        Form $form,
        DepartureModel $departureModel,
        string $typeReport,
        int $entityId
    ): Response {
        /** @var TravelTruckOrders $entityClass */
        $entityClass = $this->getDoctrine()
            ->getRepository(TravelTruckOrders::class)
            ->find($entityId);
        $result = $departureModel->generateAutomaticPdfReportWithData(
            $entityClass,
            $form,
            $typeReport
        );
        /**
         * $file = $result->file($result['pdf_path']);
         * $response =  new BinaryFileResponse($file);
         * $response->trustXSendfileTypeHeader();
         * $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
         */

        return $this->file($result['pdf_path']);
    }

    /**
     * @Route("/truck/create/report/export/allreports/{entityId<\d+>}")
     */
    public function getAllReportsInZipFile(Form $form, DepartureModel $model, int $entityId): Response
    {
        /** @var TravelTruckOrders $order */
        $order = $this->getDoctrine()->getRepository(TravelTruckOrders::class)->find($entityId);
        $result = $model->exportAllPdfReports($order, $form);

        return $this->file($result);
    }

    /**
     * @Route("/truck/removeEv")
     */
    public function removeEverything()
    {
        $all = $this->getDoctrine()->getRepository(TravelTruckOrders::class)->findAll();
        $entityManager = $this->getDoctrine()->getManager();
        /** @var TravelTruckOrders $a */
        foreach ($all as $a) {
            $orders = $a->getOrderId();
            foreach ($orders as $o) {
                $a->removeOrderId($o);
            }
            $entityManager->remove($a);
            $entityManager->flush();
        }

        return new Response('OK');
    }
}
