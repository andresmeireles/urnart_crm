<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        $dir = opendir(__DIR__.'/../../templates/form/');
        
        while ((readdir($dir)) !== false) {
            if (file_exists(__DIR__.'/../../templates/form/'.$formName.'Form.html.twig')) {
                if ($req->request->all() != []) {
                    $parameters = $req->request->all();
                    $form = new Form($formName);  

                    if (!$form->create($parameters)) {

                        $this->get('session')->set('message', $form->getMessage());
                        return $this->redirectToRoute('form/'.$formName.'Form.html.twig');
                    }

                    $form->createAndShow($parameters);
                }

                if ($this->get('session')->get('message')) {
                    $this->addFlash(
                        'message',
                        $this->get('session')->get('message')[0]
                    );
                    $this->get('session')->remove('message');
                }

                return $this->render('form/'.$formName.'Form.html.twig', [
                    'formName' => $formName
                ]);
            }
        }

        throw new \Exception('Page not found');   

    }
}
