<?php

namespace App\Controller;

use App\Config\Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class HomeController extends Controller
{
    /**
     * @Route("/home", name="home")
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/activiment")
     *
     * Redireciona paga pagina de objetivos a alcançar
     *
     * @return Response
     */
    public function activiment(): Response
    {
        return $this->render('home/activiments.html.twig');
    }
}
