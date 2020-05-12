<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\OrderPredictionModel;
use App\Utils\Exceptions\CustomException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PredictionController extends AbstractController
{
    /**
     * @Route("/prediction", name="prediction")
     */
    public function index(): Response
    {
        return $this->render('prediction/index.html.twig', [
            'controller_name' => 'PredictionController',
        ]);
    }

    /**
     * @Route("/prediction/order", name="prediction_order", methods="GET")
     */
    public function order(OrderPredictionModel $model): Response
    {
        $model->populate();
        $predictedOrders = $model->activePredictableOrders();

        return $this->render('prediction/order.html.twig', [
            'orders' => $predictedOrders
        ]);
    }

    /**
     * @Route("/prediction/order", name="chanhe_prediction_date_order", methods="POST")
     */
    public function definePrediction(Request $request, OrderPredictionModel $model): Response
    {
        if (!$this->isCsrfTokenValid('predict', $request->request->get('_csrf_token'))) {
            throw new CustomException('Token incorreto.');
        }
        $data = $request->request->all();
        $itemId = $data['itemId'];
        $predictionDate = $data['predictionDate'];
        $response = $model->setOrderPredictionDate((int) $itemId, new \DateTime($predictionDate));
        $this->addFlash($response->getType(), $response->getMessage());

        return $this->redirectToRoute('prediction_order');
    }
}
