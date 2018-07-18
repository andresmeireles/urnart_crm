<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class RegisterController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function index()
    {
        return $this->render('register/index.html.twig', [
            'controller_name' => 'RegisterController',
        ]);
    }

    /**
     * @Route("/register/customer")
     */
    public function customer()
    {

    }

    public function users()
    {
        
    }

    /**
     * @Route("/register/add/{entity}")
     */
    public function addGenericRegister($entity, Request $request)
    {
        dump($request->request->all());
        die();
    }
}
