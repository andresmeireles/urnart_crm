<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use App\Model\FeedstockModel;
use App\Entity\Feedstock;
use App\Repository\FeedstockRepository;

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
    public function feedstock()
    {
        return $this->render('storage/feedstock.html.twig');
    }

    /**
     * @Route("/storage/feedstock/{id}", name="feedstockAction", methods="POST", defaults={"id"=""})
     */
    public function feedstockAction(Request $request)
    {
        if ($request->server->get('REQUEST_METHOD') == 'POST') {
            $parameters = $request->request->all();
            $model = new FeedstockRepository(Feedstock::class);
            dump($model);
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
