<?php
/**
 * OrderController
 *  
 * @category Controller
 * @package  App\Controller
 * @author   André Meireles <andre2meireles@gmail.com>
 * @license  MIT <https://mit-license.org>
 * @link     https://bitbucket.org/andresmeireles/sysadmin
 */
namespace App\Controller;

use App\Entity\Estado;
use App\Entity\Municipio;
use App\Entity\Order;
use App\Entity\PaymentType;
use App\Entity\PessoaJuridica;
use App\Entity\Product;
use App\Entity\Transporter;
use App\Model\ListModel;
use App\Model\OrderModel;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller das paginas de pedidos
 * 
 * @category Controller
 * @package  App\Controller\OrderController
 * @author   André Meireles <andre2meireles@gmail.com>
 * @license  MIT <https://mit-license.org>
 * @link     https://bitbucket.org/andresmeireles/sysadmin
 */
class OrderController extends Controller
{
    /**
     * Redireciona para a pagina inicial dos pedidos
     * 
     * @Route("/order", name="order")
     * 
     */
    public function index(): Response
    {
        $orders = $this->getDoctrine()->getManager()->getRepository(Order::class)->findAll();
        return $this->render('order/index.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/order/list", defaults={"type"="last"})
     *
     * @param  string $type [type of list showed]
     * @param  Request $request
     * @return Response
     */
    public function list(Request $request, ListModel $model, string $type = 'last'): Response
    {
        $type = $request->query->get('type') == '' ? 'last' : $request->query->get('type');
        $order = $model->select($type);
        return $this->render('/order/lists/list.html.twig', [
            'listType' => $type,
            'order' => $order,
        ]);
    }

    /**
     * @Route("/order/create", name="createOrder", methods="GET")
     *
     * @return Response
     */
    public function viewCreateOrder(): Response
    {
        return $this->render('/order/pages/create.html.twig', [
            'products' => $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll(),
            'customers' => $this->getDoctrine()->getManager()->getRepository(PessoaJuridica::class)->findAll(),
            'payments' => $this->getDoctrine()->getManager()->getRepository(PaymentType::class)->findAll(),
            'transporters' => $this->getDoctrine()->getManager()->getRepository(Transporter::class)->findAll(),
            // for dynamic add customer
            'estado' => $this->getDoctrine()->getManager()->getRepository(Estado::class)->findAll(),
        ]);
    }

    /**
     * Cria registro no banco de dados através do pedido manual.
     * 
     * @Route("/order/createmanualorder", name="createManualOrder", methods="POST")
     *
     * @param OrderModel    $model
     * @param Request       $request
     * @return Response
     */
    public function createManualOrder(OrderModel $model, Request $request): Response
    {
        
    }

    /**
     * @Route("/order/create", methods="POST")
     *
     * @param OrderModel $model
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function createOrder(OrderModel $model, Request $request): Response
    {
        $data = $request->request->all();
        $arrData = array();
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
     * @param string $orderId
     * 
     * @return Response
     */
    public function redirectOrderActions(string $orderId): Response
    {
        $order = $this->getDoctrine()->getManager()->getRepository(Order::class)->find($orderId);
        if ($order->isClosed()) {
            $this->addFlash(
                'warning',
                "Pedido {$order->getId()} do cliente {$order->getCustomer()} está fechado e não pode ser editado"
            );
            return $this->redirectToRoute('order');
        }

        return $this->render('/order/pages/editOrder.html.twig', array(
            'order' => $this->getDoctrine()->getManager()->getRepository(Order::class)->find($orderId),
            'products' => $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll(),
            'customers' => $this->getDoctrine()->getManager()->getRepository(PessoaJuridica::class)->findAll(),
            'payments' => $this->getDoctrine()->getManager()->getRepository(PaymentType::class)->findAll(),
            'transporters' => $this->getDoctrine()->getManager()->getRepository(Transporter::class)->findAll(),
        ));
    }

    /**
     * @Route("/order/action/edit/{id}", methods="POST")
     *
     * @param string $orderId
     * 
     * @return Response
     * @throws \Exception
     */
    public function updateOrder(OrderModel $model, Request $request, string  $orderId): Response
    {
        $data = $request->request->all();
        $data['id'] = $orderId;
        $arrData = array();
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
     * @return Response
     * @throws \Exception
     */
    public function reserveOrderProducts(OrderModel $model, Request $request): Response
    {
        $hash = $request->query->get('h') ?? null;
        $orderId = $request->query->get('i') ?? null;
        if (is_null($hash) || is_null($orderId)) {
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
            "Erro... falta o hash ou ele está errado."
          );
          return $this->redirectToRoute('order');
        }
        $result = $model->reserve($orderId);
        if ($result['http_code'] == 301) {
          $this->addFlash(
            'success',
            $result['message']
          );
          return $this->redirectToRoute($request->headers->get('referer'));
        }
        $this->addFlash(
          $result['type'],
          $result['message']
        );
        return $this->redirect($request->headers->get('referer'));
    }

    /**
     * @Route("/order/action/order/{type}", methods="GET")
     *
     * @param OrderModel $model
     * @param string $type
     * @param Request $request
     * @return Response
     */
    public function runTypeUnReserveAction(OrderModel $model, string $type, Request $request): Response
    {
        $orderId = $request->query->get('i') ?? null;
        $hash = $request->query->get('h') ?? null;
        $function = $type.'Order';
        $result = $model->$function($orderId, $hash);
        if ($result['http_code'] == 301) {
            $this->addFlash(
                $result['type'],
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
     * @param string $orderId
     * @return Response
     */
    public function removeOrder(OrderModel $model, string $orderId): Response
    {
        $result = $model->removeOrder($orderId);
        $this->addFlash(
            $result['type'],
            $result['message']
        );
        return new Response($result['message'], $result['http_code'], array(
            'redirect-route' => '/order'
        ));
    }

    /**
     * @Route("/order/action/print", methods="GET")
     *
     * @param Request    $request
     * 
     * @return Response
     */
    public function showOrderToOrder(Request $request): Response
    {
        $hash = $request->query->get('h');
        $orderId = $request->query->get('i');
        if (empty($hash) || empty($orderId)) {
            $this->addFlash(
                'error',
                'Alguma informação incosistente...'
            );
            $this->redirectToRoute('order');
        }        
        return $this->render('order/printOrder/print.html.twig', [
            'order' => $this->getDoctrine()->getManager()->getRepository(Order::class)->find($orderId)
        ]);
    }
}
