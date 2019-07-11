<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\TravelAccountability;
use App\Model\ReportModel;
use App\Model\TravelAccountabilityModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TravelAccountabilityController
 * @package App\Controller
 */
final class TravelAccountabilityController extends AbstractController
{
    use CSRFTokenCheck;

    /**
     * @Route("/truck/accoutability/{accountabilityReportId<\d+>}", defaults={"accountabilityReportId"=null})
     */
    public function createtruckArrivalAccountabilityReport(?int $accountabilityReportId): Response
    {
        $accountabilityReport = $accountabilityReportId !== null ?
            $this->getDoctrine()->getRepository(TravelAccountability::class)->find($accountabilityReportId) :
            null;

        return $this->render('form/travel-reportForm.html.twig', [
            'dataFill' => $accountabilityReport ?? []
        ]);
    }

    /**
     * @Route("/travel/accountability/reports", name="accountability_show")
     */
    public function showAccoutabilityReports(): Response
    {
        $accountabilityReports = $this->getDoctrine()->getRepository(TravelAccountability::class)->findAll();

        return $this->render('report/pages/travel-accountability.html.twig', [
            'simpleView' => $accountabilityReports
        ]);
    }

    /**
     * @Route("/travel/accountability/edit/{accountabilityReportId<\d+>}", methods={"POST","GET"})
     */
    public function editAccountabilityReport(
        Request $request,
        TravelAccountabilityModel $model,
        int $accountabilityReportId
    ): Response {
        if ($request->isMethod("POST")) {
//            $refererLink = $request->headers->get('referer');
            $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'accountabilityReport');
            $accountabilityInformation = $request->request->all();
            $model->editTravelAccountability($accountabilityReportId, $accountabilityInformation);
            $this->addFlash('success', 'Relatorios editado com sucesso');

            return new Response('ok');
        }
        $accountabilityEntity = $this->getDoctrine()->getRepository(TravelAccountability::class)
            ->find($accountabilityReportId);

        return $this->render('form/travel-reportForm.html.twig', [
            'dataFill' => $accountabilityEntity
        ]);
    }

    /**
     * @Route("/travel/accountability/close/{accountabilityReportId<\d+>}", methods="POST")
     */
    public function closeAccountabilityOrderReport(Request $request, $model, int $accountabilityReportId): Response
    {
        $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'accountabilityReport');
        $reportToClose = $this->getDoctrine()->getRepository(TravelAccountability::class)
            ->find($accountabilityReportId);
        $model->deactiveGenericEntity($reportToClose);

        $this->addFlash(
            'success',
            sprintf(
                'Prestação de contas da viagem %s, do motorista %s, fechada com sucesso.',
                $reportToClose->getId(),
                $reportToClose->getDriverName()
            )
        );

        return $this->redirectToRoute('accountability_show');
    }
}