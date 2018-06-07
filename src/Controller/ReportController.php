<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Utils\Reports\Report;

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
     * @Route("/report/{report}/tagReport", methods={"GET", "HEAD", "POST"})
     */
    public function tagReport(Request $req, string $report = null)
    {
        $dir = __DIR__.

        if ($req->request->all() != []) {
            $parameters = $req->request->all();
            $report = new Report('tag');  
     
            if (!$report->create($parameters)) {

                $this->get('session')->set('message', $report->getMessage());
                return $this->redirectToRoute('tag');
            }

            $report->createAndShow($parameters);
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
