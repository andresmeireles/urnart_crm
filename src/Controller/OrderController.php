<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\OrderType;
use App\Model\OrderModel;
use App\Form\Types\OrderForm;
use App\Entity\PessoaJuridica;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class OrderController extends Controller
{
    /**
     * @Route("/order", name="order")
     */
    public function index()
    {
        return $this->render('order/index.html.twig', [
            'controller_name' => 'OrderController',
        ]);
    }

    /**
     * Redirect to order
     * 
     * @Route("/order/create", name="createOrder", methods={"GET", "POST"})
     *
     * @param OrderModel $model
     * @param Request $request
     * @return Response
     */
    public function createOrder(OrderModel $model, Request $request): Response
    {
        if ($request->server->get('REQUEST_METHOD') == 'POST') {
            $data = $request->request->all();
            foreach($data as $key => $value) {
                if (is_array($value)) {
                    unset($data[$key]);
                    $arrData[] = $value;
                }
            }

            $model->createOrder($data, $arrData);
            dump($request->request->all(), $arrData);
            die();
        }
        //<input type="text" id="order_products_0" name="order[products][0]" required="required" class="form-control" value="andre" />

        return $this->render('/order/pages/create.html.twig', [
            //'form' => $form->createView(),
            'products' => $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll(),
            'customers' => $this->getDoctrine()->getManager()->getRepository(PessoaJuridica::class)->findAll()
        ]);
    }
}
