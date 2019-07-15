<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\TravelAccountability;
use App\Model\ReportModel;
use App\Model\TravelAccountabilityModel;
use App\Utils\Andresmei\Form;
use App\Utils\Andresmei\Serializator;
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
     * @Route("/truck/accoutability/{accountabilityReportId<\d+>}", defaults={"accountabilityReportId"=0},
     *     methods={"POST", "GET"}, name="truck_accountability_index")
     */
    public function createtruckArrivalAccountabilityReport(
        Request $request,
        TravelAccountabilityModel $model,
        ?int $accountabilityReportId
    ): Response {
        if ($accountabilityReportId !== 0) {
            return $this->redirectToRoute('truck_edit', ['accountabilityReportId' => $accountabilityReportId]);
        }
        if ($request->isMethod('POST')) {
            $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'accountabilityReport');
            $accountabilityInfo = $request->request->all();
            $model->createTravelAccountability($accountabilityInfo);
            $this->addFlash('success', 'Relatório criado com sucesso.');

            return $this->redirectToRoute('truck_accountability_index');
        }

        return $this->render('form/travel-reportForm.html.twig');
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
     * @Route("/travel/accountability/edit/{accountabilityReportId<\d+>}", methods={"POST","GET"}, name="truck_edit")
     */
    public function editAccountabilityReport(
        Request $request,
        TravelAccountabilityModel $model,
        int $accountabilityReportId
    ): Response {
        $accountabilityEntity = $this->getDoctrine()->getRepository(TravelAccountability::class)
            ->find($accountabilityReportId);
        if (!$accountabilityEntity->getActive()) {
            $this->addFlash('error', 'Pedido fechado não pode ser alterado.');

            return $this->redirectToRoute('accountability_show');
        }
        if ($request->isMethod("POST")) {
            $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'accountabilityReport');
            $accountabilityInformation = $request->request->all();
            $model->editTravelAccountability($accountabilityReportId, $accountabilityInformation);
            $this->addFlash('success', 'Relatorios editado com sucesso');

            return new Response('ok');
        }

        return $this->render('form/travel-reportForm.html.twig', [
            'dataFill' => $accountabilityEntity
        ]);
    }

    /**
     * @Route("/travel/accountability/finish/{accountabilityRepoId<\d+>}", methods={"POST"},
     *     defaults={"accountabilityRepoId"=0} , name="finish_accountability_report")
     */
    public function finishAccountabilityReport(
        Request $request,
        TravelAccountabilityModel $model,
        int $accountabilityRepoId
    ): Response {
        $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'accountabilityReport');
        $entity = $this->getDoctrine()->getRepository(TravelAccountability::class)->find($accountabilityRepoId);
        $targetFinishEntity = $entity ?? new TravelAccountability();
        $model->deActiveAccountabilityReport($targetFinishEntity, $request->request->all());
        $this->addFlash('success', 'Relatorio finalizado com sucesso.');

        return $this->redirectToRoute('accountability_show');
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

    /**
     * @Route("/travel/accountability/print/{accountabilityRepoId<\d+>}", methods={"GET", "POST"},
     *     name="print_accountability_report")
     */
    public function printTravelAccountabilityReport(Request $request, Form $form, int $accountabilityRepoId): Response
    {
        $CSRFEncodedPhrase = $request->query->get('pass');
        $dateToken = (new \DateTime('now'))->format('d-m-Y');
        $this->isEncodedCSRFTokenValidPhrase($CSRFEncodedPhrase, $dateToken);
        $dataToBuildReport = $this->getDoctrine()
            ->getRepository(TravelAccountability::class)
            ->find($accountabilityRepoId);
//        dump(Serializator::serializeToArray($dataToBuildReport));
//        die;
        $responseToPrint = $form->show(
            'travel-accountability',
            ['prod' => Serializator::serializeToArray($dataToBuildReport)]
        );

        return new Response($responseToPrint['template'], 200);
    }
}
