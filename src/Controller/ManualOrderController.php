<?php declare(strict_types = 1);

namespace App\Controller;

use App\Entity\ManualOrderReport;
use App\Entity\ManualProductCart;
use App\Entity\PaymentType;
use App\Entity\Transporter;
use App\Model\ListModel;
use App\Model\OrderModel;
use App\Utils\Andresmei\NestedArraySeparator;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class ManualOrderController
 * @package App\Controller
 */
class ManualOrderController extends AbstractController
{
    use CSRFTokenCheckTrait;

    /**
     * @Route("/order/manual/{orderId<\d+>}", methods={"DELETE"})
     */
    public function removeManualOrder(int $orderId, OrderModel $orderModel): Response
    {
        $order = $this->getDoctrine()->getRepository(ManualOrderReport::class)->find($orderId);
        $cart = $this->getDoctrine()->getRepository(ManualProductCart::class)->findBy([
            'manualOrderReport' => $orderId,
        ]);
        if (!$order instanceof ManualOrderReport) {
            throw new \Exception('Nada foi mandado para ser apagado');
        }
        $orderModel->removeManualProductCart($cart);
        $orderModel->removeManualOrder($order);
        return new Response(200);
    }

    /**
     * @Route("/order/manual/list", name="manualList", methods="GET")
     */
    public function manualListing(Request $request, ListModel $listModel, PaginatorInterface $paginator): Response
    {
        $typeOfList = $request->query->get('type') ?? 'lastUpdate';
        $lists = $typeOfList === 'bydate' ? [] : $this->getDoctrine()->getRepository(ManualOrderReport::class)->findBy(
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

        return $this->render('/order/lists/'. $typeOfList .'List.html.twig', [
            'lists' => $paginatorList,
            'beginDate' => $request->query->get('beginDate'),
            'lastDate' => $request->query->get('lastDate')
        ]);
    }

    /**
     * @Route("/order/manual/{orderId<\d+>}", methods={"PUT"})
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
     * @Route("/order/manual/list/json", name="manualListJson")
     */
    public function manualListingJson(Request $request, ListModel $listModel): Response
    {
        $typeOfList = $request->query->get('type');
        $jsonReturn = $listModel->getListOrderBy('ManualOrderReport', $typeOfList);
        return new Response($jsonReturn);
    }

    /**
     * @Route("/order/createmanualorder", name="createManualOrder", methods="POST")
     */
    public function createManualOrder(OrderModel $model, Request $request): Response
    {
        $this->isEncodedCSRFTokenValidPhrase(
            $request->request->get('_csrf_token'),
            'formOrder'
        );
//        if (!$this->isCsrfTokenValid('formOrder', $request->request->get('_csrf_token'))) {
//            throw new CustomException('Token incorreto.');
//        }

        $nestedArray = new NestedArraySeparator($request->request->all());
        $result = $model->createManualReport($nestedArray->getSimpleArray(), $nestedArray->getArrayInArray());
        $this->addFlash(
            $result->getType(),
            $result->getMessage()
        );

        return $this->redirect('/order');
    }

    /**
     * @Route("order/auth/manual/print/{orderId<\d+>}")
     */
    public function printManualOrderProductAuthAllowWithdraw(int $orderId): Response
    {
        $productOrder = $this->getDoctrine()->getRepository(ManualOrderReport::class)->find($orderId);

        return $this->render('/order/printOrder/authProduct.html.twig', [
            'order' => $productOrder
        ]);
    }

    /**
     * @Route("/order/manual/{orderId<\d+>}", methods={"GET", "POST"})
     */
    public function editManualOrder(Request $request, OrderModel $model, int $orderId): Response
    {
        if ($request->getMethod() === 'POST' &&
            $this->isEncodedCSRFTokenValidPhrase(
                $request->request->get('_csrf_token'),
                'formOrder'
            )
        ) {
            $nestedArray = new NestedArraySeparator($request->request->all());
            $result = $model->editManualOrder(
                $nestedArray->getSimpleArray(),
                $nestedArray->getArrayInArray(),
                $orderId
            );
            $this->addFlash(
                $result->getType(),
                $result->getMessage()
            );

            return $this->redirectToRoute('manualList');
        }

        /** @var ManualOrderReport|null $orderToEdit */
        $orderToEdit = $this->getDoctrine()->getRepository(ManualOrderReport::class)
            ->find($orderId);
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
}
