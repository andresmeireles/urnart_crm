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
     * @Route("/storage/feedstock/{id}", name="feedstockAction", methods={"POST", "DELETE", "PUT"}, defaults={"id"=""})
     */
    public function feedstockAction(Request $request)
    {
        $method = $request->server->get('REQUEST_METHOD');
        
        if ($method == 'POST') {
            $parameters = $request->request->all();
            $model = new FeedstockModel($this->getDoctrine()->getManager());
            $model->persist($parameters);

            return new Response('Sucesso');
        } 
        
        if($method == 'DELETE') {
            die('CHEGOU AQUI AMIGINHO');
        } 
        
        if ($method == 'PUT') {
            $parameters = $request->getContent();
            dump(gettype($parameters));
            die();
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
