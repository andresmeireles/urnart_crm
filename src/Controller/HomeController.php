<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends Controller
{
    /**
     * @Route("/home", name="home")
     * @Route("/", name="index")
     */
    public function index()
    {
       /*  exec('libreoffice --headless --convert-to pdf xof.doc', $zolf, $alpha);
        if ($alpha === 1) {
            $this->addFlash(
                'error',
                'pdf não gerado'
            );
        } */
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

    /**
     * @Route("/message", name="message")
     *
     * @return Response
     */
    public function message(): Response
    {
        $x = rand(0, 9999999999999);
        return new Response($x);
    }
}
