<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class TruckController extends AbstractController
{
    /**
     * @Route("/truck")
     */
    public function index(): Response
    {
        return $this->render('truck/truck.html.twig');
    }
}