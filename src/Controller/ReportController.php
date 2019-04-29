<?php declare(strict_types=1);
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Config\NonStaticConfig;
use App\Entity\Survey;
use App\Model\ReportModel;
use App\Model\SurveyModel;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Utils\Andresmei\MyDateTime;
use App\Utils\Andresmei\StringConvertions;
use App\Utils\Exceptions\CustomException;

class ReportController extends AbstractController
{
    /**
     * Redirect to index pages of reports pages
     *
     * @Route("/report", name="report")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('report/index.html.twig');
    }

    /**
     * Redirect survey routes
     *
     * @Route("/report/survey", name="survey")
     *
     * @return Response
     */
    public function survey(NonStaticConfig $config, SurveyModel $surveyModel): Response
    {
        $quest = $config->getProperty('survey_question');
        $surveys = $this->getDoctrine()->getRepository(Survey::class)->findAll();
        $surveyByDate = $surveyModel->getSurveyData($surveys);
        return $this->render('report/pages/survey.html.twig', [
            'questions' => $quest,
            'surveys' => $surveyByDate
        ]);
    }

    /**
     * @Route("/report/productionCount/mdr", name="make_report", methods="POST")
     */
    public function makePrintReport(Request $request, ReportModel $model): Response
    {
        $repoDate = $request->request->get('repo-date');
        $report = $model->makeDailyProductionCount($repoDate);

        return $this->render('/print/report/productionCountReport.html.twig', [
            'data' => $report
        ]);
    }

    /**
     * @Route("/report/{entity}/{reportname}/print", name="make_report_print", methods="POST")
     */
    public function printAllProductionBalanceReport(
        Request $request,
        string $reportname,
        ReportModel $model
    ): Response {
        $beginDate = $request->request->get('begin-date');
        $lastDate = $request->request->get('last-date');
        $result = $model->getProductsByModelName(
            $beginDate,
            $lastDate,
        );

        $data = $result->result;
        $nameResults = array();

        foreach ($data as $value) {
            $modelHeight = sprintf("%s %s", $value['model'], $value['height']);

            if (array_key_exists($modelHeight, $nameResults)) {
                $number = $nameResults[$modelHeight];
                $nameResults[$modelHeight] = $number + $value['amount'];
                continue;
            }

            $nameResults[$modelHeight] = $value['amount'];
        }

        // $res = json_encode($nameResults);
        $res = $nameResults;
        
        return $this->render(sprintf('/print/report/%s.html.twig', $reportname), [
            'model' => $result->model,
            'height' => $result->height,
            'result' => $res
        ]);
    }

    /**
     * @Route("/report/productionCount", name="prod_count", methods="GET")
     */
    public function openProductionCountReportIndex(ReportModel $model): Response
    {
        /** @var string $today */
        $today = (new MyDateTime())->output('d-m-Y');
        $aMonthDate = (new MyDateTime())->minusDate('P1M')->output('d-m-Y');
        $aYearnDate = (new MyDateTime())->minusDate('P1Y')->output('d-m-Y');
        $productionByDayOnMonth = $model->getByDateIntervalProductAmount($aMonthDate, $today);
        $monthChart = $model->getByDateIntervalProductAmount($aYearnDate, $today, 'm-Y');
        
        return $this->render('report/pages/productionCount.html.twig', array(
            'dateChart' => $productionByDayOnMonth,
            'monthChart' => $monthChart
        ));
    }

    /**
     * @Route("/report/productionCount/make_production_report", name="make_production_report")
     */
    public function makeProductionReport(): Response
    {
        return $this->render('print/report/productionCountReport.html.twig');
    }

    /**
     * Redirect to specific report page
     *
     * @Route("/report/{reportType}", name="view_report_by_type")
     *
     * @param   string    $reportType  type of report to redirect
     *
     * @return  Response               report page
     */
    public function openReportPage(string $reportType): Response
    {
        $reportPage = sprintf('report/pages/%s.html.twig', $reportType);
        if ($reportType === 'travel-report') {
            $reportType= 'travel-accountability';
            $str = (new StringConvertions())->snakeToCamelCase($reportType);
            $repository = sprintf('App\Entity\%s', ucfirst($str));
            $travel = $this->getDoctrine()->getRepository($repository)->findAll();
        }
        $str = (new StringConvertions())->snakeToCamelCase($reportType);
        $repository = sprintf('App\Entity\%s', ucfirst($str));
        /** @var \Doctrine\ORM\EntityManager */
        $em = $this->getDoctrine()->getManager();
        $query = $em->createQuery(sprintf(
            'SELECT u.id, u.date FROM %s u',
            $repository
        ));
        $view = $query->getResult();
        return $this->render($reportPage, [
            'simpleView' => $travel ?? $view
        ]);
    }

    /**
     * Create some report registry. Give a json response.
     *
     * @Route("/api/report/{pageType}/create", methods="POST")
     *
     * @param   Request   $request     Symfony Request object.
     * @param   string    $pageType    type of page.
     *
     * @return  Response               rederized page.
     */
    public function createGenericReportRegistryAjax(Request $request, string $pageType, ReportModel $model): Response
    {
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Algo deu muito errado :(');
        }
        
        $data = $request->request->all();
        $result = $model->createGenericReport($pageType, $data);
        return new Response($result->getMessage(), $result->getHttpCode(), [
            'message-type' => $result->getType()
        ]);
    }

    /**
     * Create some report registry.
     *
     * @Route("/report/{pageType}/create", methods="POST")
     *
     * @param   Request   $request     Symfony Request object.
     * @param   string    $pageType    Type of page.
     *
     * @return  Response               Return to last referer page.
     */
    public function createGenericReportRegistry(Request $request, string $pageType, ReportModel $model): Response
    {
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Token incorreto.');
        }

        $data = $request->request->all();
        $routeLink = $request->headers->get('referer');

        if (!is_string($routeLink)) {
            $routeLink = '/';
        }

        $result = $model->createGenericReport($pageType, $data);
        $this->addFlash($result->getType(), $result->getMessage());
        return $this->redirect($routeLink);
    }

    /**
     * Return registry type.
     *
     * @Route("/report/{pageType}/list", methods="GET")
     *
     * @param   string    $pageType  Entity page.
     *
     * @return  Response             List of pages.
     */
    public function getGenericList(Request $request, string $pageType, ReportModel $model): Response
    {
        $typeOfList = $request->query->get('type') ?? 'last';

        $beginDate = $request->query->get('beginDate');
        $lastDate = $request->query->get('lastDate');

        if (!is_null($beginDate) || !is_null($lastDate)) {
            $byDateResults = $model->searchByDate(ucwords($pageType), 'boletoVencimento', 'u', $beginDate, $lastDate);
        }

        $listOfResults = $model->getGenericList($pageType, $typeOfList);
        $templateFile = sprintf('report/pages/%s/lists.html.twig', $pageType);

        return $this->render($templateFile, [
            'typeOfList' => $listOfResults->typeOfList,
            'simpleView' => $listOfResults->consultResults ?? $byDateResults ?? array(),
            'beginDate' => $beginDate ?? null,
            'lastDate' => $lastDate ?? null
        ]);
    }

    /**
     * Return edit view.
     *
     * @Route("/report/{entity}/edit/{idConsult<\d+>}", methods="GET")
     *
     * @param   string    $entity     Entity name.
     * @param   int       $idConsult  Id to fetch data from database.
     *
     * @return  Response              Edit page.
     */
    public function viewEditGeneric(string $entity, int $idConsult): Response
    {
        $fullQualifiedEntity = sprintf('App\Entity\%s', ucfirst($entity));
        $registryToEdit = $this->getDoctrine()->getRepository($fullQualifiedEntity)->find($idConsult);
        $template = sprintf('report/pages/%s/edit.html.twig', $entity);

        return $this->render($template, [
            'registry' => $registryToEdit
        ]);
    }

    /**
     * Return edit view.
     *
     * @Route("/report/{entity}/edit/{idConsult<\d+>}", methods="POST")
     *
     * @param   Request   $request    Symfony Request object.
     * @param   string    $entity     Entity name.
     * @param   int       $idConsult  Id to fetch data from database.
     *
     * @return  Response              Edit page.
     */
    public function editRegisterGeneric(Request $request, string $entity, int $idConsult, ReportModel $model): Response
    {
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Algo deu muito errado :(');
        }

        $data = $request->request->all();
        $result = $model->editRegistryGeneric($entity, $idConsult, $data);

        $this->addFlash($result->getType(), $result->getMessage());

        return $this->redirect('/report/boleto/list');
    }

    /**
     * Get data from entity.
     *
     * @Route("/api/report/{entity}/{consultId}")
     *
     * @param   string    $entity     Entity for consult.
     * @param   int       $consultId  Id for consult.
     *
     * @return  Response              Single registry data.
     */
    public function getSingleDataGenericAjax(string $entity, int $consultId, ReportModel $model): Response
    {
        $entity = sprintf('App\Entity\%s', ucwords($entity));
        $result = $model->serializedGenericConsult($entity, $consultId);
        return new Response($result);
    }

    /**
     * Cria pesquisa de satisfação
     *
     * @Route("/report/create/survey", name="createSurvey")
     *
     * @return Response
     */
    public function createSurvey(Request $request, SurveyModel $surveyModel): Response
    {
        $data = $request->request->all();
        $customerData = (new NestedArraySeparator($data))->getArrayInArray();
        $surveyDate = (new MyDateTime())->format('d.m.Y');
        $result = $surveyModel->createRegistry($customerData, $surveyDate, 'travel_survey');
        return new Response($result['msg'], 200);
    }

    /**
     * Send surveys to databases
     *
     * @Route("/report/survey/send", name="sendSurveys", methods="POST")
     *
     * @return Response
     */
    public function sendSurveys(Request $request, SurveyModel $surveyModel): Response
    {
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Algo deu muito errado :(');
        }

        $data = $request->request->all();
        $customerId = $data['customerId'];
        $surveyReferenceDate = $data['surveyReferenceDate'];
        unset($data['customerId']);
        unset($data['surveyReferenceDate']);

        $response = $surveyModel->saveData($data, $customerId, $surveyReferenceDate);
        return new Response($response->getMessage(), $response->getHttpCode());
    }

    /****************************************************
    ************** SPECIFIC ENTITY METHODS **************
    *****************************************************/

    /**
     * Change status of Boleto. Specific function.
     *
     * @Route("/report/boleto/status/{boletoId}", methods="POST")
     *
     * @param   ReportModel  $model     Report with change functions.
     * @param   int          $boletoId  Boleto identification.
     *
     * @return  Response             Redirect to page.
     */
    public function boletoChangeStatus(Request $request, int $boletoId, ReportModel $model): Response
    {
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Algo deu muito errado :(');
        }

        $boletoData = $request->request->all();
        $refererLink = $request->headers->get('referer');
        if (!is_string($refererLink)) {
            throw new \Exception('O caminho dado não é valido.', 1);
        }

        $result = $model->boletoChangeStatus($boletoId, $boletoData);
        $this->addFlash($result->getType(), $result->getMessage());

        return $this->redirect($refererLink);
    }

    /**
     * Redirect To chart generator page.
     *
     * @Route("/report/boleto/reportCreator/{reportName}", methods="GET")
     *
     * @param   Request      $request       Request model object.
     * @param   string       $reportName    Name of report.
     * @param   ReportModel  $model         ReportModel for search function.
     *
     * @return  Response                    Chart page.
     */
    public function createBoletoReport(Request $request, string $reportName, ReportModel $model): Response
    {
        $beginDate = $request->query->get('beginDate');
        $lastDate = $request->query->get('lastDate');

        $functionName = sprintf('generateBoleto%s', ucwords($reportName));
        $reportData = $model->$functionName($beginDate, $lastDate);
        $template = sprintf('/report/pages/boleto/templates/%s.html.twig', $reportName);

        return $this->render($template, [
            'statusCount' => $reportData->boletosStatusCount,
            'statusNames' => $reportData->statusNames ?? '',
            'totalValue' => $reportData->totalValue,
            'payedValue' => $reportData->boletoPayedValue,
            'beginDate' => $beginDate,
            'lastDate' => $lastDate
        ]);
    }
}
