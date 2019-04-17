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

use App\Entity\Order;
use App\Entity\Estado;
use App\Entity\Product;
use App\Model\ListModel;
use App\Model\OrderModel;
use App\Entity\PaymentType;
use App\Entity\Transporter;
use App\Entity\PessoaJuridica;
use App\Entity\ManualOrderReport;
use App\Entity\ManualProductCart;
use App\Utils\Exceptions\CustomException;
use App\Utils\Andresmei\NestedArraySeparator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Controller das paginas de pedidos
 *
 * @category Controller
 * @package  App\Controller\OrderController
 * @author   André Meireles <andre2meireles@gmail.com>
 * @license  MIT <https://mit-license.org>
 * @link     https://bitbucket.org/andresmeireles/sysadmin
 */
class OrderController extends AbstractController
{
    /**
     * Redireciona para a pagina inicial dos pedidos
     *
     * @Route("/order", name="order")
     *
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $orders = $this->getDoctrine()->getManager()->getRepository(Order::class)->findAll();
        $manualOrder = $this->getDoctrine()->getRepository(ManualOrderReport::class)->findBy(array(), array(
            'lastUpdate' => 'DESC'
        ));

        $paginatedOrders = $paginator->paginate(
            $manualOrder,
            $request->query->getInt('page', 1)
        );

        return $this->render('order/index.html.twig', [
            'orders' => $orders,
            'manualOrder' => $paginatedOrders
        ]);
    }

    /**
     * @Route("/order/list", defaults={"type"="last"})
     *
     * @param  Request $request
     * @return Response
     */
    public function list(Request $request, ListModel $model): Response
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
     * @Route("/order/create", methods="POST")
     *
     * @param OrderModel $model
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function createOrder(OrderModel $model, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Algo deu muito errado :(');
        }
        
        $data = $request->request->all();
        $arrData = array();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                unset($data[$key]);
                $arrData[] = $value;
            }
        }
        $result = $model->createOrder($data, $arrData);
        if ($result->getHttpCode() === 400) {
            $this->addFlash('error', $result->getMessage());
            return $this->redirectToRoute('createOrder');
        }
        $this->addFlash('success', $result->getMessage());
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
        if ($order instanceof Order && $order->isClosed()) {
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
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Algo deu muito errado :(');
        }

        $data = $request->request->all();
        $data['id'] = $orderId;
        $arrData = array();
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                unset($data[$key]);
                $arrData[] = $value;
            }
        }
        $result = $model->updateOrder($data, $arrData);
        if ($result->getHttpCode() === 400) {
            $this->addFlash('error', $result->getMessage());
            return $this->redirectToRoute('createOrder');
        }
        if ($result->getHttpCode() === 301) {
            $this->addFlash('success', sprintf("Pedido %s atualizado com sucesso", $data['id']));
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
            $this->addFlash('error', 'Erro ao criar reserva');
            return $this->redirectToRoute('order');
        }
        
        if ($hash !== hash('ripemd160', 'valido')) {
            $this->addFlash('error', "Erro... falta o hash ou ele está errado.");
            return $this->redirectToRoute('order');
        }
        
        $result = $model->reserve($orderId);
        
        if (!is_string($request->headers->get('referer'))) {
            throw new \Exception('Link antigo não existe. Abortado');
        }

        if ($result->getHttpCode() === 301) {
            $this->addFlash('success', $result->getMessage());

            return $this->redirectToRoute($request->headers->get('referer'));
        }

        $this->addFlash($result->getType(), $result->getMessage());

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
        $result = $model->removeOrder((int) $orderId);
        $this->addFlash(
            $result->getType(),
            $result->getMessage()
        );
        return new Response($result->getMessage(), $result->getHttpCode(), array(
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
        $printRepository = sprintf('App\Entity\%s', $request->query->get('r'));
        if (empty($hash) || empty($orderId)) {
            $this->addFlash(
                'error',
                'Alguma informação incosistente...'
            );
            $this->redirectToRoute('order');
        }
        return $this->render('order/printOrder/print.html.twig', [
            'order' => $this->getDoctrine()->getManager()->getRepository($printRepository)->find($orderId)
        ]);
    }

    /************************
     *** MANUAL FUNCTIONS ***
     ************************/

    /**
     * @Route("/order/manual/list", name="manualList")
     *
     * @param   Request     $request
     * @param   ListModel   $listModel
     * @return  Response
     */
    public function manualListing(Request $request, ListModel $listModel): Response
    {
        $typeOfList = $request->query->get('type') ?? 'lastUpdate';
        $lists = $typeOfList === 'bydate' ? '' : $this->getDoctrine()->getRepository(ManualOrderReport::class)->findBy(
            array(),
            array($typeOfList => 'ASC')
        );

        if ($request->query->get('beginDate') !== null || $request->query->get('lastDate') !== null) {
            $lists = $listModel->getListByDate(
                'ManualOrderReport',
                $request->query->get('beginDate'),
                $request->query->get('lastDate')
            );
            $typeOfList = 'bydate';
        }

        return $this->render('/order/lists/'. $typeOfList .'List.html.twig', [
            'lists' => $lists,
            'beginDate' => $request->query->get('beginDate'),
            'lastDate' => $request->query->get('lastDate')
        ]);
    }

    /**
     * @Route("/order/manual/list/json", name="manualListJson")
     *
     * @param Request $request
     * @param ListModel $listModel
     * @return Response
     */
    public function manualListingJson(Request $request, ListModel $listModel): Response
    {
        $typeOfList = $request->query->get('type');
        $jsonReturn = $listModel->getListOrderBy('ManualOrderReport', $typeOfList);
        return new Response($jsonReturn);
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
        if (!$this->isCsrfTokenValid('formOrder', $request->request->get('_csrf_token'))) {
            throw new CustomException('Token incorreto.');
        }

        $nestedArray = new NestedArraySeparator($request->request->all());
        $result = $model->createManualReport($nestedArray->getSimpleArray(), $nestedArray->getArrayInArray());
        $this->addFlash(
            $result->getType(),
            $result->getMessage()
        );

        return $this->redirect('/order');
    }

    /**
     * @Route("/order/manual/{orderId<\d+>}", methods={"POST"})
     *
     * @param int $orderId
     * @return Response
     */
    public function editManualOrder(Request $request, OrderModel $model, int $orderId): Response
    {
        if (!$this->isCsrfTokenValid('formOrder', $request->request->get('_csrf_token'))) {
            throw new CustomException('Token incorreto.');
        }

        $nestedArray = new NestedArraySeparator($request->request->all());
        $result = $model->editManualOrder($nestedArray->getSimpleArray(), $nestedArray->getArrayInArray(), $orderId);
        $this->addFlash(
            $result->getType(),
            $result->getMessage()
        );

        return $this->redirectToRoute('manualList');
    }

    /**
     * @Route("/order/manual/{orderId<\d+>}", methods={"GET"})
     *
     * @param int $orderId
     * @return Response
     */
    public function viewEditManualOrder(int $orderId): Response
    {
        /** @var ManualOrderReport|null */
        $orderToEdit = $this->getDoctrine()->getRepository(ManualOrderReport::class)->find($orderId);
        
        if ($orderToEdit === null) {
            $this->addFlash('error', sprintf(
                'Pedido n° <b>%d</b> não exite. Verique se pedido foi corretamente cadastrado.',
                $orderId
            ));
            return $this->redirectToRoute('order');
        }
        if (!$orderToEdit->getActive()) {
            $this->addFlash('warning', sprintf(
                'Pedido %d fechado não pode ser alterado. Fechado em %s',
                $orderToEdit->getId(),
                $orderToEdit->getLastUpdate()
            ));
            return $this->redirectToRoute('manualList');
        }

        $transporters = $this->getDoctrine()->getRepository(Transporter::class)->findAll();
        $paymentType = $this->getDoctrine()->getRepository(PaymentType::class)->findAll();

        return $this->render('/order/pages/editManualOrder.html.twig', [
            'order' => $orderToEdit,
            'transporters' => $transporters,
            'payments' => $paymentType
        ]);
    }

    /**
     * @Route("/order/manual/{orderId<\d+>}", methods={"PUT"})
     *
     * @param int $orderId
     * @param OrderModel $orderModel
     * @throws \Exception
     * @return Response
     */
    public function closeManualOrder(int $orderId, OrderModel $orderModel): Response
    {
        $order = $this->getDoctrine()->getRepository(ManualOrderReport::class)->find($orderId);
        if (!$order instanceof ManualOrderreport) {
            throw new \Exception('Erro. pedido não pode ser fechado.');
        }
        $orderModel->closeManualOrder($order);
        return new Response(200);
    }

    /**
     * @Route("/order/manual/{orderId<\d+>}", methods={"DELETE"})
     *
     * @param int $orderId
     * @return Response
     */
    public function removeManualOrder(int $orderId, OrderModel $orderModel): Response
    {
        $order = $this->getDoctrine()->getRepository(ManualOrderReport::class)->find($orderId);
        $cart = $this->getDoctrine()->getRepository(ManualProductCart::class)->findBy(array(
            'manualOrderReport' => $orderId,
        ));
        if (!$order instanceof ManualOrderReport) {
            throw new \Exception('Nada foi mandado para ser apagado');
        }
        $orderModel->removeManualProductCart($cart);
        $orderModel->removeManualOrder($order);
        return new Response(200);
    }
}
