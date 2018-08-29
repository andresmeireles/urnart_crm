<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Utils\Generic\Crud;
use App\Model\ProductModel;
use App\Model\FeedstockModel;
use App\Entity\Feedstock;
use App\Entity\FeedstockInventory;
use App\Form\FeedstockForm;
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
     * 
     * @param Crud $getter
     * 
     * @return Response
     */
    public function index(Crud $getter): Response
    {
        return $this->render('storage/index.html.twig', [
            'feed' => $getter->get('feedstock'),
            'prod' => $getter->get('product')
        ]);
    }

    /**
     * @Route("/storage/feedstock", name="feedstock", methods="GET")
     * 
     * @param Crud $getter
     * @return Response
     */
    public function feedstock(Crud $getter): Response
    {
        return $this->render('storage/feedstock.html.twig', [
            'product' => $getter->get('feedstock'),
            'feedstock' => $getter->get('feedstockInventory'),
            'unit' => $getter->get('unit'),
            'departament' => $getter->get('departament'),
        ]);
    }

    /**
     * @Route("/storage/feedstockAction/{id}", name="feedstockAction", methods={"POST", "DELETE", "GET"}, defaults={"id"=null})
     * 
     * @param Request $reques
     * @param int|null $id
     * @param Crud $getter
     * @return Response
     */
    public function feedstockAction(Request $request, ?int $id, Crud $getter): Response
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
            
            $feedForm = $this->createForm(FeedstockForm::class, $update);

            return $this->render('storage/forms/feedstockForm.html.twig', [
                'feedForm' => $feedForm->createView(),
                'product' => $getter->getRegisterById('feedstock', $id),
                'unit' => $getter->get('unit'),
                'departament' => $getter->get('departament'),
             ]);
        } else {
            return $this->createNotFoundException();
        }
    }

    /**
     * @Route("/storage/feedstockAction/update/{id}", methods="POST")
     * 
     * @param Feedstock $model
     * @param Request $request
     * @return Response
     */
    public function updateFeedStock(FeedstockModel $model, Request $request, int $id): Response
    {
        $data = $request->request->all();
        $model->update($data, $id);
        return $this->redirectToRoute('feedstock');
    }

    /**
     * @Route("/storage/feedstock/in", methods="POST")
     * 
     * @param Feedstock $model 
     * @param Request $request
     * @return Response 
     */
    public function feedIn(FeedstockModel $model, Request $request): Response
    {
        $data = $request->request->all();
        $model->feedIn($data);
        
        return new Response('Sucesso! :)', Response::HTTP_OK);
    }

    /**
     * @Route("/storage/feedstock/out", methods="POST")
     *
     * @param FeedstockModel $model
     * @param Request $request
     * @return Response
     */
    public function feedOut(FeedstockModel $model, Request $request): Response
    {
        $data = $request->request->all();
        $result = $model->feedOut($data);

        return new Response($result['message'], $result['http_code']);
    }

    /**
     * @Route("/storage/product", name="showProd")
     * 
     * @param Crud $getter
     * 
     * @return Response
     */
    public function prodStock(Crud $getter): Response
    {
        return $this->render('/storage/product.html.twig', [
            'product' => $getter->get('product')
        ]);
    }

    /**
     * @Route("/storage/productAction", methods="POST")
     *
     * @param ProductModel $model
     * @param array $data
     * @param Request $require
     * @return Response
     */
    public function productAction(ProductModel $model, Request $request): Response
    {
        $data = $request->request->all();
        $response = $model->insert($data);
        return new Response($response['message'], $response['http_code']);
    }
}
