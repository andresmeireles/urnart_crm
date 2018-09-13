<?php

namespace App\Controller;

use App\Model\OrderModel;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\PaymentType;
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
    public function index(Request $request)
    {
        $orders = $this->getDoctrine()->getManager()->getRepository(Order::class)->findAll();

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/order/create", name="createOrder", methods="GET")
     *
     * @param OrderModel $model
     * @param Request $request
     * @return Response
     */
    public function viewCreateOrder(): Response
    {
        return $this->render('/order/pages/create.html.twig', [
            'products' => $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll(),
            'customers' => $this->getDoctrine()->getManager()->getRepository(PessoaJuridica::class)->findAll(),
            'payments' => $this->getDoctrine()->getManager()->getRepository(PaymentType::class)->findAll()
        ]);
    }

    /**
     * @Route("/order/create", methods="POST")
     *
     * @param OrderModel $model
     * @param Request $request
     * @return Response
     */
    public function createOrder(OrderModel $model, Request $request): Response
    {
        $data = $request->request->all();
        foreach($data as $key => $value) {
            if (is_array($value)) {
                unset($data[$key]);
                $arrData[] = $value;
            }
        }

        $result = $model->createOrder($data, $arrData);

        if ($result['http_code'] === '400') {
            $this->addFlash(
                'error',
                $result['message']
            );

            return $this->redirectToRoute('createOrder');
        }

        $this->addFlash(
            'success',
            $result['message']
        );

        return $this->redirectToRoute('order');
    }
}
