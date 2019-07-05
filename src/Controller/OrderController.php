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
use App\Entity\ManualOrderReport;
use App\Entity\ManualProductCart;
use App\Entity\Order;
use App\Entity\PaymentType;
use App\Entity\PessoaJuridica;
use App\Entity\Product;
use App\Entity\Transporter;
use App\Model\ListModel;
use App\Model\OrderModel;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Utils\Exceptions\CustomException;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
class OrderController extends AbstractController
{
    use CSRFTokenCheckTrait;

    /**
     * @Route("/order", name="order")
     */
    public function index(
        Request $request,
        PaginatorInterface $paginator,
        OrderModel $orderModel
    ): Response {
        $query = $request->query;
        if ($query->get('query') !== null) {
            $entity = sprintf('App\Entity\%s', $query->get('entity'));
            $field = $query->get('field');
            $queryMessage = $query->get('query');
            $formatQuery = sprintf(
                "SELECT u FROM %s u WHERE u.%s LIKE '%%%s%%'",
                $entity,
                $field,
                $queryMessage
            );
            $result = $orderModel->dqlConsult($formatQuery);
            $manualOrderSearchResult = $paginator->paginate(
                $result,
                $request->query->getInt('page', 1)
            );
        }
        $orders = $this->getDoctrine()->getManager()->getRepository(Order::class)->findAll();
        $manualOrder = $this->getDoctrine()->getRepository(ManualOrderReport::class)->findBy([], [
            'lastUpdate' => 'DESC'
        ]);

        $paginatedOrders = $paginator->paginate(
            $manualOrder,
            $request->query->getInt('page', 1)
        );

        return $this->render('order/index.html.twig', [
            'orders' => $orders ?? [],
            'manualOrder' => $manualOrderSearchResult ?? $paginatedOrders ?? []
        ]);
    }

    /**
     * @Route("order/search", name="simple.search", methods="GET")
     */
    public function simpleSearchEngine(
        Request $request,
        PaginatorInterface $paginator,
        OrderModel $orderModel
    ): Response {
        $query = $request->query;
        $routeName = $query->get('name');
        $entity = sprintf(
            'App\Entity\%s',
            $query->get('entity')
        );
        $field = $query->get('field');
        $queryMessage = $query->get('query');
        $fomartQuery = sprintf("SELECT u FROM %s u WHERE u.%s = '%s'", $entity, $field, $queryMessage);
        $result = $orderModel->dqlConsult($fomartQuery);
        $manualOrderResult = $paginator->paginate(
            $result,
            $request->query->getInt('page', 1)
        );
        
        return $this->redirectToRoute($routeName, ['manualOrderSearch' => $manualOrderResult], 301);
    }

    /**
     * @Route("/order/list", defaults={"type"="last"})
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
     */
    public function viewCreateOrder(Request $request, OrderModel $model): Response
    {
        if ($request->getMethod() === 'POST' &&
            $this->isEncodedCSRFTokenValidPhrase(
                $request->request->get('_csrf_token'),
                'autenticateBoleto'
            )
        ) {
            $data = $request->request->all();
            $arrData = [];
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
     * @Route("/order/action/edit/{id}", methods={"GET", "POST"}, name="edit.order")
     */
    public function redirectOrderActions(Request $request, OrderModel $model, int $orderId): Response
    {
        $order = $this->getDoctrine()->getManager()->getRepository(Order::class)->find($orderId);
        if ($order instanceof Order && $order->isClosed()) {
            $this->addFlash(
                'warning',
                sprintf(
                    'Pedido %s de %s está fechado e não pode ser editado.',
                    $order->getId(),
                    $order->getCustomer()
                )
            );
            return $this->redirectToRoute('order');
        }

        if ($request->getMethod() === 'POST' &&
            $this->isEncodedCSRFTokenValidPhrase(
                $request->request->get('_csrf_token'),
                'autenticateBoleto'
            )
        ) {
            $data = $request->request->all();
            $data['id'] = $orderId;
            $arrData = [];
            foreach ($data as $key => $value) {
                if (is_array($value)) {
                    unset($data[$key]);
                    $arrData[] = $value;
                }
            }
            $result = $model->updateOrder($data, $arrData);
            $this->addFlash(
                $result->getType(),
                $result->getMessage()
            );
            return $this->redirectToRoute('order');
        }

        return $this->render('/order/pages/editOrder.html.twig', [
            'order' => $this->getDoctrine()->getManager()->getRepository(Order::class)
                ->find($orderId),
            'products' => $this->getDoctrine()->getManager()->getRepository(Product::class)
                ->findAll(),
            'customers' => $this->getDoctrine()->getManager()->getRepository(PessoaJuridica::class)
                ->findAll(),
            'payments' => $this->getDoctrine()->getManager()->getRepository(PaymentType::class)
                ->findAll(),
            'transporters' => $this->getDoctrine()->getManager()->getRepository(Transporter::class)
                ->findAll(),
        ]);
    }

    /**
     * @Route("/order/action/remove/{id}", methods="DELETE")
     */
    public function removeOrder(OrderModel $model, string $orderId): Response
    {
        $result = $model->removeOrder((int) $orderId);
        $this->addFlash(
            $result->getType(),
            $result->getMessage()
        );

        return new Response($result->getMessage(), $result->getHttpCode(), [
            'redirect-route' => '/order'
        ]);
    }

    /**
     * @Route("/order/action/print", methods="GET")
     */
    public function showOrderToOrder(Request $request): Response
    {
        $hash = $request->query->get('h');
        $orderId = $request->query->get('i');
        $printRepository = sprintf('App\Entity\%s', $request->query->get('r'));
        if ($hash === null || $orderId === null) {
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
}
