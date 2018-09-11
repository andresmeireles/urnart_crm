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
    public function index(Request $request)
    {
        return $this->render('order/index.html.twig');
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

            $result = $model->createOrder($data, $arrData);

            if ($result['http_code'] === '400') {
                die('deu errado mano');
            }

            $this->addFlash(
                'success',
                $result['message']
            );

            return $this->redirectToRoute('order');
        }
        
        return $this->render('/order/pages/create.html.twig', [
            'products' => $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll(),
            'customers' => $this->getDoctrine()->getManager()->getRepository(PessoaJuridica::class)->findAll()
        ]);
    }
}
