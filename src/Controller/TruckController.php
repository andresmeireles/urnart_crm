<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\ManualOrderReport;

class TruckController extends AbstractController
{
    /**
     * @Route("/truck")
     */
    public function index(): Response
    {
        return $this->render('truck/truck.html.twig');
    }

    /**
     * @Route("truck/create", methods="GET", name="create.truck.report")
     */
    public function create(): Response
    {
        /** @var ManualOrderReportRepository|ManualOrderReport */
        $ordersId = $this->getDoctrine()->getRepository(ManualOrderReport::class)->someFieldsConsult('id', 'customerName');
        
        return $this->render('truck/pages/createTruckRepo.html.twig', [
            'orders' => $ordersId
        ]);
    }
}
