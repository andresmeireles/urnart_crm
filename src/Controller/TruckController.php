<?php declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

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
