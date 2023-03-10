<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\Boleto;
use App\Model\BoletoModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class BoletoReportController extends AbstractController
{
    use CSRFTokenCheck;

    /**
     * @Route("/boleto/index")
     */
    public function renderBoletoIndexPage(): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQuery(
            sprintf(
                'SELECT u.id, u.boletoPaymentDate FROM %s u',
                Boleto::class
            )
        );
        $view = $query->getResult();

        return $this->render('report/pages/boleto.html.twig', [
            'simpleView' => $view,
        ]);
    }

    /**
     * @Route("/report/boleto/status/{boletoId}", methods="POST")
     */
    public function boletoChangeStatus(
        Request $request,
        int $boletoId,
        BoletoModel $model
    ): Response {
        $this->isEncodedCSRFTokenValidPhrase(
            $request->request->get('_csrf_token'),
            'autenticateBoleto'
        );

        $boletoData = $request->request->all();
        $refererLink = $request->headers->get('referer');
        if (! is_string($refererLink)) {
            throw new \Exception('O caminho dado não é valido.', 1);
        }

        $result = $model->boletoChangeStatus($boletoId, $boletoData);
        $this->addFlash($result->getType(), $result->getMessage());

        return $this->redirect($refererLink);
    }

    /**
     * @Route("/report/boleto/reportCreator/{reportName}", methods="GET")
     */
    public function createBoletoReport(
        Request $request,
        string $reportName,
        BoletoModel $model
    ): Response {
        $beginDate = $request->query->get('beginDate');
        $lastDate = $request->query->get('lastDate');

        $functionName = sprintf('generateBoleto%s', ucwords($reportName));
        $reportData = $model->{$functionName}($beginDate, $lastDate);
        $template = sprintf('/report/pages/boleto/templates/%s.html.twig', $reportName);

        return $this->render($template, [
            'statusCount' => $reportData->boletosStatusCount,
            'statusNames' => $reportData->statusNames ?? '',
            'totalValue' => $reportData->totalValue,
            'payedValue' => $reportData->boletoPayedValue,
            'beginDate' => $beginDate,
            'lastDate' => $lastDate,
        ]);
    }
}
