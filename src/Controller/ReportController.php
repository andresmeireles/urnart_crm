<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="report")
     */
    public function index()
    {
        return $this->render('report/index.html.twig');
    }

    /**
     * @Route("/report/survey", name="survey")
     *
     * @return Response
     */
    public function survey()
    {
        return $this->render('report/pages/survey.html.twig');
    }
}
