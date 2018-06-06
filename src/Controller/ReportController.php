<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Utils\Reports\TagReportCreator;

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
        if ($this->get('session')->get('message')) {
            $this->addFlash(
                'message',
                $this->get('session')->get('message')[0]
            );
            $this->get('session')->remove('message');
        }

    	return $this->render('report/tagReport.html.twig');
    }

    /**
     * @Route("/tagReport", methods={"POST"})
     */
    public function createReport(Request $req)
    {
        $parameters = $req->request->all();
        $report = new TagReportCreator();  
        if (!$report->create($parameters)) {
            $this->get('session')->set('message', $report->getMessage());
            return $this->redirectToRoute('tag');
        }
        $report->show();
    }
}
