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
     * @Route("/order/manual/{orderId<\d+>}", methods={"DELETE"})
     */
    public function removeManualOrder(int $orderId, OrderModel $orderModel): Response
    {
        $order = $this->manualOrderRepository->find($orderId);
        $cart = $this->manualOrderRepository->findBy([
            'manualOrderReport' => $orderId,
        ]);
        if (! $order instanceof ManualOrderReport) {
            throw new \Exception('Nada foi mandado para ser apagado');
        }
        $orderModel->removeManualProductCart((array) $cart);
        $orderModel->removeManualOrder($order);
        return new Response('200');
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
     * @Route("/order/createmanualorder", name="createManualOrder", methods="POST")
     */
    // public function createManualOrder(Request $request, ManualOrderModel $model): Response
    // {
    //     $this->isEncodedCSRFTokenValidPhrase(
    //         $request->request->get('_csrf_token'),
    //         'formOrder'
    //     );
    //     $result = $model->insertManualOrder($request->request->all());
    //     $this->addFlash(
    //         $result->getType(),
    //         $result->getMessage()
    //     );

    //     return $this->redirect('/order');
    // }

    /**
     * @Route("/order/manual/status/{orderId<\d+>}", name="change_order_status", methods="POST")
     */
    public function changeOrderStatus(Request $request, int $orderId): Response
    {
        $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'changeStatus');
        $order = $this->manualOrderRepository->find($orderId);
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
     * @Route("/order/manual/{orderModelId}", methods="GET", name="no_async_manual_order")
     * @Route("/order/async/editManualOrder/{orderModelId<\d+>}", methods={"GET", "POST"},
     *     name="async_edit_manual_order")
     */
    public function editManualOrderAsync(Request $request, ManualOrderModel $model, int $orderModelId): Response
    {
        if ($request->isMethod('POST')) {
            $this->isEncodedCSRFTokenValidPhrase($request->request->get('_csrf_token'), 'formOrder');
            $response = $model->editOrderModelById($orderModelId, $request->request->all());
            $this->addFlash($response->getType(), $response->getMessage());

            return new Response($response->getMessage(), $response->getHttpCode());
        }
        $orderRegistry = $this->manualOrderRepository->find($orderModelId);
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
        $productOrder = $this->getDoctrine()->getRepository(ManualOrderReport::class)->find($orderId);
        if (!$productOrder->getActive()) {
            $this->addFlash('warning', 'Pedido fechado.');

            return $this->redirectToRoute('order');
        }

        return $this->render('/order/printOrder/authProduct.html.twig', [
            'order' => $productOrder,
        ]);
    }

//    /**
//     * @Route("/order/manual/{orderId<\d+>}", methods={"GET", "POST"})
//     */
//    public function editManualOrder(Request $request, OrderModel $model, int $orderId): Response
//    {
//        if ($request->getMethod() === 'POST' &&
//            $this->isEncodedCSRFTokenValidPhrase(
//                $request->request->get('_csrf_token'),
//                'formOrder'
//            )
//        ) {
//            $nestedArray = new NestedArraySeparator($request->request->all());
//            $result = $model->editManualOrder(
//                $nestedArray->getSimpleArray(),
//                $nestedArray->getArrayInArray(),
//                $orderId
//            );
//            $this->addFlash(
//                $result->getType(),
//                $result->getMessage()
//            );
//
//            return $this->redirectToRoute('manualList');
//        }
//
//        /** @var ManualOrderReport|null $orderToEdit */
//        $orderToEdit = $this->getDoctrine()->getRepository(ManualOrderReport::class)
//            ->find($orderId);
//        if ($orderToEdit === null) {
//            $this->addFlash('error', sprintf(
//                'Pedido n° <b>%d</b> não exite. Verique se pedido foi corretamente cadastrado.',
//                $orderId
//            ));
//            return $this->redirectToRoute('order');
//        }
//        if (! $orderToEdit->getActive()) {
//            $this->addFlash('warning', sprintf(
//                'Pedido %d fechado não pode ser alterado. Fechado em %s',
//                $orderToEdit->getId(),
//                $orderToEdit->getLastUpdate()
//            ));
//            return $this->redirectToRoute('manualList');
//        }
//        $transporters = $this->getDoctrine()->getRepository(Transporter::class)->findAll();
//        $paymentType = $this->getDoctrine()->getRepository(PaymentType::class)->findAll();
//
//        return $this->render('/order/pages/editManualOrder.html.twig', [
//            'order' => $orderToEdit,
//            'transporters' => $transporters,
//            'payments' => $paymentType,
//        ]);
//    }
}
