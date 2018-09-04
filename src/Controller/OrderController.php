<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Form\Types\OrderForm;
use App\Entity\PessoaJuridica;
use App\Entity\Product;
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
     * @param Request $request
     * @return Response
     */
    public function createOrder(Request $request): Response
    {
        $orderForm = new OrderForm();

        $form = $this->createForm(OrderType::class, $orderForm);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            dump($form->getData());
            die();
        }

        //<input type="text" id="order_products_0" name="order[products][0]" required="required" class="form-control" value="andre" />

        return $this->render('/order/pages/create.html.twig', [
            'form' => $form->createView(),
            'products' => $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll(),
            'customers' => $this->getDoctrine()->getManager()->getRepository(PessoaJuridica::class)->findAll()
        ]);
    }
}
