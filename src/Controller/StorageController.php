<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Form\FeedstockForm;
use App\Model\ProductModel;
use App\Utils\Generic\Crud;
use App\Model\FeedstockModel;
use App\Entity\Product;
use App\Entity\Feedstock;
use App\Entity\FeedstockInventory;
use App\Form\FeedstockInventoryForm;
use App\Repository\FeedstockRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StorageController extends AbstractController
{
    /**
     * @Route("/storage", name="storage")
     *
     * @param Crud $getter
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
     * @param Request $request
     * @param int|null $productId
     * @param Crud $getter
     * @return Response
     * @throws \Exception
     */
    public function feedstockAction(Request $request, ?int $productId, Crud $getter): Response
    {
        $productId = $productId === null ? '' : $productId;
        $method = $request->server->get('REQUEST_METHOD');
        $model = new FeedstockModel($this->getDoctrine()->getManager());
        $entityManager = $this->getDoctrine()->getManager();

        if ($method == 'POST') {
            $parameters = $request->request->all();
            $model->persist($parameters);

            return new Response('Sucesso');
        }

        if ($method == 'DELETE') {
            if (!is_int($productId)) {
                throw new \Exception('Não é um número');
            }

            $item = $entityManager->getRepository(Feedstock::class)->find($productId);
            try {
                $entityManager->remove($item);
                $entityManager->flush();
            } catch (\Exception $e) {
                throw new \Exception($e->getMessage());
            }

            return new Response(200, Response::HTTP_OK, array(
            'redirect-route' => '/storage/product'
            ));
        }
        
        if ($method == 'GET') {
            $update = $this->getDoctrine()->getManager()->getRepository(Feedstock::class)->find($productId);
            $feedForm = $this->createForm(FeedstockForm::class, $update);

            return $this->render('storage/forms/feedstockForm.html.twig', [
                'feedForm' => $feedForm->createView(),
                'product' => $getter->getRegisterById('feedstock', $productId),
                'unit' => $getter->get('unit'),
                'departament' => $getter->get('departament'),
            ]);
        }
        
        return $this->createNotFoundException();
        
    }

    /**
     * @Route("/storage/feedstockAction/update/{id}", methods="POST")
     *
     * @param FeedstockModel    $model
     * @param Request           $request
     * @param int               $productId
     * @return Response
     */
    public function updateFeedStock(FeedstockModel $model, Request $request, int $productId): Response
    {
        $data = $request->request->all();
        $model->update($data, $productId);

        return $this->redirectToRoute('feedstock');
    }

    /**
     * @Route("/storage/feedstock/in", methods="POST")
     *
     * @param Feedstock   $model
     * @param Request     $request
     * 
     * @return Response
     * @throws \Exception
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
     * @throws \Exception
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
     * @throws \Exception
     */
    public function addProduct(ProductModel $model, Request $request): Response
    {
        $data = $request->request->all();
        $response = $model->insert($data);
        $this->addFlash(
            $response['type'],
            $response['message']
        );
        return $this->redirectToRoute('showProd');
    }

    /**
     * Redirect to update page.
     *
     * @Route("/storage/productAction/{id<\d+?>}", methods="GET")
     *
     * @param string $id
     * 
     * @return Response
     */
    public function redirectToUpdate($productId): Response
    {
        return $this->render('/storage/forms/productForm.html.twig', [
            'product' => $this->getDoctrine()->getManager()->getRepository(Product::class)->find($productId)
            ]);
    }
    
    /**
     * Update page. Accepts only numbers as id
     *
     * @Route("/storage/product/update/{id}", methods="POST", requirements={"page"="\d+"})
     *
     * @param ProductModel $model
     * @param string $productId
     * @return Response
     * @throws \Exception
     */
    public function updateProduct(ProductModel $model, Request $request, string $productId): Response
    {
        $productId = (int) $productId;
        $data = $request->request->all();
        $result = $model->update($data, $productId);

        if ($result['http_code'] == 203) {
            return $this->render('/storage/forms/productForm.html.twig', [
                'product' => $data,
                'flash' => 'Algo deu errado...'
            ]);
        }

        return $this->redirectToRoute('showProd', array(), 301);
    }
    
    /**
     * Remove page. Accepts only numbers as id
     *
     * @Route("/storage/productAction/{id}", methods="DELETE", requirements={"page"="\d+"})
     *
     * @param ProductModel $model
     * @param [type] $productId
     * @return Response
     */
    public function removeProduct(ProductModel $model, $productId): Response
    {
        $productId = (int) $productId;
        $result = $model->remove($productId);

        return new Response($result['message'], $result['http_code'], array(
            'redirect-route' => '/storage/product'
        ));
    }

    /**
     * @Route("/storage/product/in", methods="PUT")
     *
     * @param Request $request
     * @return Response
     */
    public function productIn(ProductModel $model, Request $request): Response
    {
        $data = json_decode($request->getContent());
        $data = $data->data;
        $result = $model->productIn($data);

        return new Response($result['message'], $result['http_code']);
    }
}
