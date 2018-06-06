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
     * @Route("/tagReport", name="tag", methods={"GET", "HEAD", "POST"})
     */
    public function tagReport(Request $req)
    {
        if ($req->request->all() != []) {
            $parameters = $req->request->all();
            $report = new ReportFactory();  
     
            if (!$report->create($parameters)) {

                $this->get('session')->set('message', $report->getMessage());
                return $this->redirectToRoute('tag');
            }

            $report->show();
        }

        if ($this->get('session')->get('message')) {
            $this->addFlash(
                'message',
                $this->get('session')->get('message')[0]
            );
            $this->get('session')->remove('message');
        }

        return $this->render('report/tagReport.html.twig');
    }
}
