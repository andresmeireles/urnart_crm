<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\Generic\GenericSetter;

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
     * @Route("/register/add/{entity}", methods="POST")
     */
    public function addGenericRegister(string $entity, GenericSetter $setter, Request $request)
    {
        $setter->set($entity, $request->request->all());
        return new Response (
            $setter->getMessage(), 
            Response::HTTP_OK,
            array('type-message' => $setter->getTypeMessage())
        );     
    }
}
