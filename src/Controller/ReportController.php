<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Utils\Reports\TagReport;

class ReportController extends Controller
{
    /**
     * @Route("/report", name="report")
     */
    public function index()
    {
        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
        ]);
    }

    /**
     * @Route("/tagReport", name="tag", methods={"GET", "HEAD"})
     */
    public function tagReport()
    {
    	return $this->render('report/tagReport.html.twig');
    }

    /**
     * @Route("/tagReport", methods={"POST"})
     */
    public function createReport(Request $req)
    {
        $parameters = $req->request->all();
        $report = new TagReport();
        dump($parameters, $report->create($parameters));
    }
}
