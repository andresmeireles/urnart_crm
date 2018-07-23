<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\Generic\GenericSetter;
use App\Utils\Generic\GenericGetter;

class RegisterController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function index(GenericGetter $getter)
    {
        return $this->render('register/index.html.twig', [
            'departament' => $getter->get('departament'),
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

    /**
     * @Route("/register/get/{entity}", methods="POST")
     */
    public function getGenericRegister(string $entity, GenericGetter $getter)
    {
        $table = $getter->getJsonData($entity);
        return new Response($table);
    }

    /**
     * @Route("/register/remove/{entity}", methods="POST")
     */
    public function getGenericRemover(Request $request)
    {
        $object = json_decode($request->getContent());
        die();
    }
}
