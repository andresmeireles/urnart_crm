<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\ManualOrderReport;
use App\Entity\ModelName;
use App\Entity\PaymentType;
use App\Entity\Transporter;
use App\Model\ListModel;
use App\Model\ManualOrderModel;
use App\Model\OrderModel;
use App\Repository\ManualOrderReportRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ManualOrderController
 *
 * @package App\Controller
 */
final class ManualOrderController extends AbstractController
{
    use CSRFTokenCheck;

    private ManualOrderReportRepository $manualOrderRepository;

    public function __construct(ManualOrderReportRepository $manualOrderReportRepository)
    {
        $this->manualOrderRepository = $manualOrderReportRepository;
    }

    /**
     * @Route("/order/manual/list", name="manualList", methods="GET")
     */
    public function manualListing(Request $request, ListModel $listModel, PaginatorInterface $paginator): Response
    {
        $typeOfList = $request->query->get('type') ?? 'lastUpdate';
        $lists = $typeOfList === 'bydate' ? [] : $this->manualOrderRepository->findBy(
            [],
            [$typeOfList => 'ASC']
        );
        if ($request->query->get('beginDate') !== null || $request->query->get('lastDate') !== null) {
            $lists = $listModel->getListByDate(
                'ManualOrderReport',
                $request->query->get('beginDate'),
                $request->query->get('lastDate')
            );
            $typeOfList = 'bydate';
        }
        $paginatorList = $paginator->paginate(
            $lists,
            $request->query->getInt('page', 1)
        );

        return $this->render('/order/lists/' . $typeOfList . 'List.html.twig', [
            'lists' => $paginatorList,
            'beginDate' => $request->query->get('beginDate'),
            'lastDate' => $request->query->get('lastDate'),
        ]);
    }

    /**
     * @Route("/order/manual/{orderId<\d+>}", methods={"PUT"})
     */
    public function closeManualOrder(int $orderId, OrderModel $orderModel): Response
    {
        if (!$this->manualOrderRepository->find($orderId)->getActive()) {
            return new Response('Pedido já está fechado');
        }
        $order = $this->manualOrderRepository->find($orderId);
        if (!$order instanceof ManualOrderreport) {
            throw new \Exception('Erro. pedido não pode ser fechado.');
        }
        $orderModel->closeManualOrder($order);
        return new Response('200');
    }

    /**
     * @Route("/order/manual/list/json", name="manualListJson")
     */
    public function manualListingJson(Request $request, ListModel $listModel): Response
    {
        $typeOfList = $request->query->get('type');
        $jsonReturn = $listModel->getListOrderBy('ManualOrderReport', $typeOfList);
        return new Response(json_encode($jsonReturn));
    }

    /**
     * @Route("/order/manual/status/{orderId<\d+>}", name="change_order_status", methods="POST")
     */
    public function changeOrderStatus(Request $request, int $orderId): Response
    {
        $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'changeStatus');
        $order = $this->manualOrderRepository->find($orderId);
        if (!$order->getActive()) {
            $this->addFlash(
                'error',
                sprintf("Pedido fechado não pode ter seu status alterado", $orderId)
            );

            return $this->redirectToRoute('order');
        }
        if ($this->manualOrderRepository->changeOrderStatus($order, (int) $request->request->get('status'))) {
            $this->addFlash(
                'success',
                sprintf("Pedido %s teve seu status alterado com sucesso", $orderId)
            );

            return $this->redirectToRoute('order');
        }

        $this->addFlash(
            'error',
            sprintf("Pedido %s teve seu status alterado sem sucesso", $orderId)
        );

        return $this->redirectToRoute('order');
    }

    /**
     * @Route("/order/async/createManualOrder", name="async_create_manual_order", methods={"POST"})
     */
    public function createManualOrderAsync(Request $request, ManualOrderModel $model): Response
    {
        $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'formOrder');
        $response = $model->createNewOrderModel($request->request->all());
        if ($response->getHttpCode() === 200) {
            $this->addFlash($response->getType(), $response->getMessage());
        }

        return new Response($response->getMessage(), $response->getHttpCode());
    }

    /**
     * @Route("/order/manual/{orderModelId}", methods={"GET", "POST"}, name="no_async_manual_order")
     * @Route("/order/async/editManualOrder/{orderModelId<\d+>}", methods={"GET", "POST"},
     *     name="async_edit_manual_order")
     */
    public function editManualOrderAsync(Request $request, ManualOrderModel $model, int $orderModelId): Response
    {
        if ($request->isMethod('POST')) {
            $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'formOrder');
            $order = $this->manualOrderRepository->find($orderModelId);
            if ($order->getActive() === false || $order->getOrderStatus() === 3) {
                $this->addFlash('error', 'Pedido não pode ser alterado');

                return $this->redirectToRoute('order');
            }
            $response = $model->editOrderModelById($orderModelId, $request->request->all());
            $this->addFlash($response->getType(), $response->getMessage());

            return new Response($response->getMessage(), $response->getHttpCode());
        }
        $orderRegistry = $this->manualOrderRepository->find($orderModelId);
        if ($orderRegistry->getActive() === false || $orderRegistry->getOrderStatus() === 0) {
            $this->addFlash('warning', 'Pedido fechado não pode ser alterado.');

            return $this->redirectToRoute('order');
        }
        if ($orderRegistry === null) {
            $this->addFlash('error', 'Item não existe');

            return $this->redirectToRoute('order');
        }

        return $this->render('/order/pages/editManualOrder.html.twig', [
            'products' => $this->getDoctrine()->getRepository(ModelName::class)->findAll(),
            'payments' => $this->getDoctrine()->getRepository(PaymentType::class)->findAll(),
            'transporters' => $this->getDoctrine()->getRepository(Transporter::class)->findAll(),
            'order' => $orderRegistry
        ]);
    }


    /**
     * @Route("order/auth/manual/print/{orderId<\d+>}")
     */
    public function printManualOrderProductAuthAllowWithdraw(int $orderId): Response
    {
        /** @var ManualOrderReport */
        $productOrder = $this->manualOrderRepository->find($orderId);
        if (!$productOrder->getActive()) {
            $this->addFlash('warning', 'Pedido fechado.');

            return $this->redirectToRoute('order');
        }

        return $this->render('/order/printOrder/authProduct.html.twig', [
            'order' => $productOrder,
        ]);
    }
}
