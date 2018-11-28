<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Filesystem\Filesystem;
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
     * @Route("/forms/{formName}/pdf", methods="POST")
     * 
     * Recebe parametros, cria e envia para download arquivo pdf.
     * 
     * @param  Request $request  
     * @param  string  $formName 
     * @return Response            
     */
    public function sendPdfForm(Request $request, string $formName, Form $form): Response
    {
        if (empty($request->request->all())) {
            echo 'Nenhum dado enviado';
        }
        $data = $request->request->all();
        $result = $form->returnSelectedFromType('pdf', $formName, $data);

        //check if file exists
        $file = $result['pdf_path'];
        $fs = new Filesystem();
        if (!$fs->exists($file)) {
            throw $this->createNotFoundException('File not found.');
        }           

        // send message
        $this->addFlash(
            $result['type'],
            'Sucesso!'
        );

        // send file to download
        $response =  new BinaryFileResponse($file);
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);
 
        return $response;
        //return $this->file($file); <-- Alternativa mais simples :)
    }
}
