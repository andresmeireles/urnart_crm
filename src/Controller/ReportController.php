<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\SurveyModel;
use App\Config\NonStaticConfig;
use App\Entity\Survey;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Utils\Andresmei\MyDateTime;

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
     * Redirect to specific report page
     *
     * @Route("/report/{reportType}")
     * 
     * @param   string    $reportType  type of report to redirect
     *
     * @return  Response                report page
     * @throws  \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function openReportPage(string $reportType): Response
    {
        $reportPage = sprintf('report/pages/%s.html.twig', $reportType);
        return $this->render($reportPage);
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
        $data = $request->request->all();
        $customerId = $data['customerId'];
        $surveyReferenceDate = $data['surveyReferenceDate'];
        unset($data['customerId']);
        unset($data['surveyReferenceDate']);

        $response = $surveyModel->saveData($data, $customerId, $surveyReferenceDate);
        return new Response($response->getMessage(), $response->getHttpCode());
    }
}
