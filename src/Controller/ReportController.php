<?php declare(strict_types = 1);
namespace App\Controller;

use App\Config\NonStaticConfig;
use App\Entity\Survey;
use App\Model\ReportModel;
use App\Model\SurveyModel;
use App\Utils\Andresmei\MyDateTime;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Utils\Andresmei\StringConvertions;
use App\Utils\Exceptions\CustomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ModelName;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Model\ProductionCountModel;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="report")
     */
    public function index(): Response
    {
        return $this->render('report/index.html.twig');
    }

    /**
     * @Route("/report/survey", name="survey")
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
     * @Route("/report/productionCount/mr", name="make_report_custom", methods="POST")
     */
    public function makeRepo(Request $request, ReportModel $model): Response
    {
        $type = $request->request->get('type');
        $dateOne = $request->request->get('begin-date');
        $dateTwo = $request->request->get('last-date');
        $result = $model->makeReportByType($type, $dateOne, $dateTwo);
        $template = sprintf('/print/report/%s.html.twig', $result->template);

        return $this->render($template, [
            'data' => $result->result,
            'bDate' => $dateOne,
            'lDate' => $dateTwo
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
            $lastDate
        );
        $data = $result->result;
        $nameResults = [];
        $modelResults = [];
        $heightResults = [];
        $finalTotal = 0;
        foreach ($data as $value) {
            $modelHeight = sprintf("%s%s%s", $value['model'], $value['height'], $value['obs'] ?? '');
            $modelHeight = trim($modelHeight);
            if (array_key_exists($modelHeight, $nameResults)) {
                $number = $nameResults[$modelHeight];
                $nameResults[$modelHeight] = $number + $value['amount'];
                continue;
            }

            $nameResults[$modelHeight] = $value['amount'];
        }
        foreach ($data as $value) {
            $modelName = sprintf('%s', $value['model']);
            if (array_key_exists($modelName, $modelResults)) {
                $number = $modelResults[$modelName];
                $modelResults[$modelName] = $number + $value['amount'];
                continue;
            }
            $modelResults[$modelName] = $value['amount'];
        }
        foreach ($data as $value) {
            $heightName = sprintf('%s%s', $value['height'], $value['obs']);
            $finalTotal += $value['amount'];
            if (array_key_exists($heightName, $heightResults)) {
                $number = $heightResults[$heightName];
                $heightResults[$heightName] = $number + $value['amount'];
                continue;
            }
            $heightResults[$heightName] = $value['amount'];
        }
        // $res = json_encode($nameResults);
        $res = $nameResults;
        $modelTotals = $modelResults;
        $heightTotals = $heightResults;
        
        return $this->render(sprintf('/print/report/%s.html.twig', $reportname), [
            'model' => $result->model,
            'modelTotal' => $modelTotals,
            'height' => $result->height,
            'heightTotal' => $heightTotals,
            'finalTotal' => $finalTotal,
            'bDate' => $beginDate,
            'lDate' => $lastDate,
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
        $modelNames = $this->getDoctrine()
            ->getRepository(ModelName::class)
            ->findBy([], ['name' => 'ASC']);

        return $this->render('report/pages/productionCount.html.twig', [
            'dateChart' => $productionByDayOnMonth,
            'monthChart' => $monthChart,
            'modelNames' => $modelNames,
        ]);
    }

    /**
     * @Route("/report/productionCount/make_production_report", name="make_production_report")
     */
    public function makeProductionReport(): Response
    {
        return $this->render('print/report/productionCountReport.html.twig');
    }

    /**
     * @Route("/report/{reportType}", name="view_report_by_type")
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
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();
        $query = $entityManager->createQuery(sprintf(
            'SELECT u.id, u.date FROM %s u',
            $repository
        ));
        $view = $query->getResult();
        
        return $this->render($reportPage, [
            'simpleView' => $travel ?? $view
        ]);
    }

    /**
     * @Route("/api/report/{pageType}/create", methods="POST")
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
     * @Route("/report/{pageType}/create", methods="POST")
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
     * @Route("/report/productionCount/createByCatchModel", methods="POST")
     */
    public function createByCatchModel(Request $request, ProductionCountModel $productionCountModel): Response
    {
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Token incorreto.');
        }
        $request->request->remove('_csrf_token');
        $result = array_map(static function ($value) {
            return $value;
        }, $request->request->all());
        $insertionResult = $productionCountModel->createProductionCount($result);
        $this->addFlash(
            $insertionResult->getType(),
            $insertionResult->getMessage()
        );
        $routeLink = $request->headers->get('referer');

        return $this->redirect(is_string($routeLink) ? $routeLink : '/');
    }

    /**
     * @Route("/get/ModelsModel")
     */
    public function coiso()
    {
        $res = $this->getDoctrine()->getRepository(ModelName::class)->findAll();
        $modelNames = array_map(static function ($value) {
            if ([] !== $value->getColors()) {
                $arrays = [];
                foreach ($value->getColors() as $color) {
                    $arrays[] = [
                        'v' => sprintf("%s-%s", $value->getId(), $color),
                        'n' => sprintf(
                            '%s %s %s %s',
                            $value->getName(),
                            $value->getHeight(),
                            $color,
                            $value->getSpecificity()
                        ),
                    ];
                }
                return $arrays;
            }
            return [
                'v' => $value->getId(),
                'n' => sprintf(
                    '%s %s %s',
                    $value->getName(),
                    $value->getHeight(),
                    $value->getSpecificity()
                )
            ];
        }, $res);
        foreach ($modelNames as $key => $value) {
            if (!array_key_exists('n', $value)) {
                array_map(static function ($item) use (&$modelNames) {
                    $modelNames[] = $item;
                }, $value);
                unset($modelNames[$key]);
            }
        }

        return new Response(json_encode($modelNames));
    }

    /**
     * @Route("/report/{pageType}/list", methods="GET")
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
            'simpleView' => $listOfResults->consultResults ?? $byDateResults ?? [],
            'beginDate' => $beginDate ?? null,
            'lastDate' => $lastDate ?? null
        ]);
    }

    /**
     * @Route("/report/{entity}/edit/{idConsult<\d+>}", methods="GET")
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
     * @Route("/report/{entity}/edit/{idConsult<\d+>}", methods="POST")
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
     * @Route("/api/report/{entity}/{consultId}")
     */
    public function getSingleDataGenericAjax(string $entity, int $consultId, ReportModel $model): Response
    {
        $entity = sprintf('App\Entity\%s', ucwords($entity));
        $result = $model->serializedGenericConsult($entity, $consultId);
        return new Response($result);
    }

    /**
     * @Route("/report/create/survey", name="createSurvey")
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
     * @Route("/report/survey/send", name="sendSurveys", methods="POST")
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
     * @Route("/report/boleto/status/{boletoId}", methods="POST")
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
     * @Route("/report/boleto/reportCreator/{reportName}", methods="GET")
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
