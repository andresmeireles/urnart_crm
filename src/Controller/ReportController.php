<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
    public function tagReport(Request $req)
    {
        dump($req->server->add(['ola']), $req->server->all());
        die();
    	return $this->render('report/tagReport.html.twig');
    }

    /**
     * @Route("/tagReport", methods={"POST"})
     */
    public function createReport(Request $req)
    {
        $parameters = $req->request->all();
        $report = new TagReport();
        if (!$report->create($parameters)) {
            return $this->redirectToRoute('tag');;
        }
        die();
        $report->show();
    }
}
