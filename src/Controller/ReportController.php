<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Model\SurveyModel;

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
    public function survey(): \Symfony\Component\HttpFoundation\Response
    {
        return $this->render('report/pages/survey.html.twig');
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
        dump($data, $request->getContent());
        die();
        $response = $surveyModel->saveData($data);
        return new Response('OK');
    }
}
