<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\Andresmei\Form;

class FormController extends Controller
{
    /**
     * @Route("/forms", name="form")
     */
    public function index()
    {
        return $this->render('form/index.html.twig');
    }

    /**
     * @Route("/forms/{formName}", methods={"GET"})
     */
    public function findFormTemplate(string $formName)
    {
        $templateDir = opendir(__DIR__.'/../../templates/form');
        // to read dir content
        while (readdir($templateDir) !== false) {
            if (file_exists(__DIR__.'/../../templates/form/'.$formName.'Form.html.twig')) {
                return $this->render('form/'.$formName.'Form.html.twig', [
                    'formName' => $formName
                ]);
            }    
        }
        throw new \Exception('Page not found');   
    }

    /**
     * @Route("/forms/{formName}", methods="POST")
     * @param  Request $request  
     * @param  string  $formName 
     * @return Response            
     */
    public function printForm(Request $request, string $formName, Form $form)
    {
        if (empty($request->request->all())) {
            echo 'Nenhum dado enviado';
        }
        $data = $request->request->all();
        $result = $form->returnSelectedFromType('show', $formName, $data);
        return new Response($result['template']);
    }

    /**
     * @Route("/forms/pdf/{formName}", methods="POST")
     * @param  Request $request  
     * @param  string  $formName 
     * @return Response            
     */
    public function sendPdfForm(Request $request, string $formName, Form $form)
    {
        if (empty($request->request->all())) {
            echo 'Nenhum dado enviado';
        }
        $data = $request->request->all();
        $result = $form->returnSelectedFromType('pdf', $formName, $data);
        return new Response($result['template']);
    }
}
