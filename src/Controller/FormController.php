<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\Form\Form;

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
     * @Route("/forms/{formName}", methods={"GET", "HEAD", "POST"})
     */
    public function createReport(Request $req, string $formName)
    {
        $dir = opendir(__DIR__.'/../../templates/form/');
        while ((readdir($dir)) !== false) {
            if (file_exists(__DIR__.'/../../templates/form/'.$formName.'Form.html.twig')) {
                if ($req->request->all() != []) {
                    $parameters = $req->request->all();
                    $form = new Form($formName);  

                    $body = $form->create($parameters);

                    if ($form->fail()) {
                        $response =  json_encode($form->getMessage());
                        return new Response($response, 200);
                    }
                    
                    $form->show($body);
                }

                /**
                if ($session->get('error')) {
                    foreach ($session->get('error') as $message) {
                        $this->addFlash(
                            'error',
                            $message
                        );
                    }
                    $session->remove('error');
                }
                */
                return $this->render('form/'.$formName.'Form.html.twig', [
                    'formName' => $formName
                ]);
            }
        }

        throw new \Exception('Page not found');   

    }
}
