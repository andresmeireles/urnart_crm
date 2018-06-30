<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Cookie;
use App\Utils\Validation\ValidatorJson;
use App\Utils\Form\Form;

class FormController extends Controller
{
    /**
     * @Route("/form", name="form")
     */
    public function index()
    {
        return $this->render('form/index.html.twig');
    }

    /**
     * @Route("/form/{formName}", methods={"GET", "HEAD", "POST"})
     */
    public function createReport(Request $req, string $formName)
    {
        $session = new Session;
        $session->start();
        $dir = opendir(__DIR__.'/../../templates/form/');
        
        while ((readdir($dir)) !== false) {
            if (file_exists(__DIR__.'/../../templates/form/'.$formName.'Form.html.twig')) {
                if ($req->request->all() != []) {
                    $parameters = $req->request->all();
                    $form = new Form($formName);  

                    $body = $form->create($parameters);

                    if ($form->fail()) {
                        $session->set('error', $form->getMessage());
                        return $this->redirect('/form/'.$formName);
                    }

                    $form->show($body);
                }

                if ($session->get('error')) {
                    foreach ($session->get('error') as $message) {
                        $this->addFlash(
                            'error',
                            $message
                        );
                    }
                    $session->remove('error');
                }

                return $this->render('form/'.$formName.'Form.html.twig', [
                    'formName' => $formName
                ]);
            }
        }

        throw new \Exception('Page not found');   

    }
}
