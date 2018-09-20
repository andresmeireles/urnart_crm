<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\Product;
use App\Model\OrderModel;
use App\Entity\PaymentType;
use App\Entity\Transporter;
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
            'payments' => $this->getDoctrine()->getManager()->getRepository(PaymentType::class)->findAll(),
            'transporters' => $this->getDoctrine()->getManager()->getRepository(Transporter::class)->findAll()
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

    /**
     * @Route("/order/action/edit/{id}", methods="GET")
     *
     * @param $id
     * @return Response
     */
    public function redirectOrderActions($id): Response
    {
        return $this->render('/order/pages/editOrder.html.twig', array(
            'order' => $this->getDoctrine()->getManager()->getRepository(Order::class)->find($id),
            'products' => $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll(),
            'customers' => $this->getDoctrine()->getManager()->getRepository(PessoaJuridica::class)->findAll(),
            'payments' => $this->getDoctrine()->getManager()->getRepository(PaymentType::class)->findAll(),
            'transporters' => $this->getDoctrine()->getManager()->getRepository(Transporter::class)->findAll()
        ));
    }

    /**
     * @Route("/order/action/edit/{id}", methods="POST")
     *
     * @param $id
     * @return Response
     */
    public function updateOrder(OrderModel $model, Request $request, $id): Response
    {
        $data = $request->request->all();
        $data['id'] = $id;

        foreach($data as $key => $value) {
            if (is_array($value)) {
                unset($data[$key]);
                $arrData[] = $value;
            }
        }

        $result = $model->updateOrder($data, $arrData);

        if ($result['http_code'] === '400') {
            $this->addFlash(
                'error',
                $result['message']
            );

            return $this->redirectToRoute('createOrder');
        }

        if ($result['http_code'] == '301') {
            $this->addFlash(
                'success',
                "Pedido {$data['id']} atualizado com sucesso"
            );
        }

        return $this->redirectToRoute('order');
    }

    /**
     * @Route("/order/action/reserve", methods="GET")
     *
     * @return Response [description]
     */
    public function reserveOrder(OrderModel $model, Request $request): Response
    {
        $hash = $request->query->get('h') ?? null;
        $id = $request->query->get('i') ?? null;
        if (is_null($hash) || is_null($id)) {
            $this->addFlash(
              'error',
              'Erro ao criar reserva'
            );

            return $this->redirectToRoute('order');
        }

        $trueHash = hash('ripemd160', 'valido');
        if ($hash !== $trueHash) {
          $this->addFlash(
            'error',
            "Erro ao fazer reserva, falta o hash ou ele está errado."
          );
          return $this->redirectToRoute('order');
        }

        $result = $model->reserve($id);

        if ($result['http_code'] == 301) {
          $this->addFlash(
            'success',
            $result['message']
          );

          return $this->redirectToRoute('order');
        }

        $this->addFlash(
          $result['type'],
          $result['message']
        );

        return $this->redirectToRoute('order');


    }

    /**
     * @Route("/order/action/remove/{id}", methods="DELETE")
     *
     * @param OrderModel $model
     * @param $id
     * @return Response
     */
    public function removeOrder(OrderModel $model, $id): Response
    {
        $id = $id;
        $result = $model->removeOrder($id);

        $this->addFlash(
            $result['type'],
            $result['message']
        );

        return new Response($result['message'], $result['http_code'], array(
            'redirect-route' => '/order'
        ));
    }
}
