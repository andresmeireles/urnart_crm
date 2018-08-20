<?php

namespace App\Controller;

use App\Entity\Feedstock;
use App\Form\FeedstockForm;
use App\Utils\Generic\Crud;
use App\Model\FeedstockModel;
use App\Entity\FeedstockInventory;
use App\Form\FeedstockInventoryForm;
use App\Repository\FeedstockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
     * @Route("/storage/feedstockAction/{id}", name="feedstockAction", methods={"POST", "DELETE", "GET"}, defaults={"id"=null})
     */
    public function feedstockAction(Request $request, ?int $id)
    {
        $id = $id == null ? '' : (int) $id;
        $method = $request->server->get('REQUEST_METHOD');
        $model = new FeedstockModel($this->getDoctrine()->getManager());
        $em = $this->getDoctrine()->getManager();

        if ($method == 'POST') {
            $parameters = $request->request->all();
            $model->persist($parameters);

            return new Response('Sucesso');
        } 
        
        if($method == 'DELETE') {
            if (!is_int($id)) {
                throw new \Exception('Não é um número');
            }
            
            $item = $em->getRepository(Feedstock::class)->find($id);

            try {
                $em->remove($item);
                $em->flush();
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

            return new Response(200, Response::HTTP_OK);
        } 
        
        if ($method == 'GET') {
            $update = $this->getDoctrine()->getManager()->getRepository(Feedstock::class)->find($id);
            $inventoryUpdate = $update->getFeedstockInventory();
            
            $feedForm = $this->createForm(FeedstockForm::class, $update);
            $invForm = $this->createForm(FeedstockInventoryForm::class, $inventoryUpdate);

            return $this->render('storage/forms/feedstockForm.html.twig', [
                'feedForm' => $feedForm->createView(),
                'invForm' => $invForm->createView()
             ]);
        } else {
            return $this->createNotFoundException();
        }
    }

    /**
     * @Route("/storage/prodStock", name="showProd")
     */
    public function prodStock()
    {

    }
}
