<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\SurveyModel;
use App\Config\NonStaticConfig;
use App\Entity\Survey;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Utils\Andresmei\MyDateTime;

class ReportController extends AbstractController
{
    /**
     * Redirect to index pages of reports pages
     *
     * @Route("/report", name="report")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('report/index.html.twig');
    }

    /**
     * Redirect survey routes
     *
     * @Route("/report/survey", name="survey")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function survey(NonStaticConfig $config): \Symfony\Component\HttpFoundation\Response
    {
        $quest = $config->getProperty('survey_question');
        $surveys = $this->getDoctrine()->getRepository(Survey::class)->findAll();
        return $this->render('report/pages/survey.html.twig', [
            'questions' => $quest,
            'survey' => $surveys
        ]);
    }

    /**
     * Cria pesquisa de satisfação
     *
     * @Route("/report/create/survey", name="createSurvey")
     * 
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function createSurvey(Request $request, SurveyModel $surveyModel): \Symfony\Component\HttpFoundation\Response
    {
        $data = $request->request->all();
        $customerData = (new NestedArraySeparator($data))->getArrayInArray();
        $surveyDate = (new MyDateTime())->format('d.m.Y');
        $result = $surveyModel->createRegistry($customerData, $surveyDate, 'travel_survey');
        dump($customerData, $surveyDate);
        die();
    }

    /**
     * Send surveys to databases
     * 
     * @Route("/report/survey/send", name="sendSurveys", methods="POST")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function sendSurveys(Request $request, SurveyModel $surveyModel): \Symfony\Component\HttpFoundation\Response
    {
        $data = $request->request->all();
        $customerId = $data['customerId'];
        $surveyReferenceDate = $data['surveyReferenceDate'];
        unset($data['customerId']);
        unset($data['surveyReferenceDate']);
        $response = $surveyModel->saveData($data, $customerId, $surveyReferenceDate);
        die('chegou aqui sem falhas!!!');
        return new Response('OK');
    }
}
