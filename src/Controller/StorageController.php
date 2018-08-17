<?php

namespace App\Controller;

use App\Entity\Feedstock;
use App\Utils\Generic\Crud;
use App\Model\FeedstockModel;
use App\Repository\FeedstockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\FeedstockInventory;
use App\Form\FeedstockForm;

class StorageController extends Controller
{
    /**
     * @Route("/storage", name="storage")
     */
    public function index()
    {
        return $this->render('storage/index.html.twig', [
            'controller_name' => 'StorageController',
        ]);
    }

    /**
     * @Route("/storage/feedstock", name="feedstock", methods="GET")
     */
    public function feedstock(Crud $getter)
    {
        return $this->render('storage/feedstock.html.twig', [
            'feedstock' => $getter->get('feedstockInventory'),
            'unit' => $getter->get('unit'),
            'departament' => $getter->get('departament'),
        ]);
    }

    /**
     * @Route("/storage/feedstockz/{id}", name="feedstockAction", methods={"POST", "DELETE", "PUT", "GET"}, defaults={"id"=""})
     */
    public function feedstockAction(Request $request)
    {
        $method = $request->server->get('REQUEST_METHOD');
        $model = new FeedstockModel($this->getDoctrine()->getManager());

        if ($method == 'POST') {
            $parameters = $request->request->all();
            $model->persist($parameters);

            return new Response('Sucesso');
        } 
        
        if($method == 'DELETE') {
            die('CHEGOU AQUI AMIGINHO');
        } 
        
        if ($method == 'GET') {
            //$parameters = (array) json_decode($request->getContent());
            //$model->update($parameters);
            //dump($parameters);
            //$update = $this->getDoctrine()->getManager()->getRepository(Feedstock::class)->find(2);
            $form = $this->createForm(FeedstockForm::class);
            return $this->render('/forms/formTlp.html.twig', [
                'form' => $form->createView(),
             ]);
        } else {
            return $this->createNotFoundException();
        }

        return $this->redirectToRoute('feedstock');
    }

    /**
     * @Route("/storage/prodStock", name="showProd")
     */
    public function prodStock()
    {

    }
}
