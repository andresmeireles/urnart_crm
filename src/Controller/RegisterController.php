<?php

namespace App\Controller;

use App\Utils\Generic\Crud;
use App\Utils\Generic\GenericGetter;
use App\Utils\Generic\GenericSetter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
    public function getGenericRemover(string $entity, Request $request, Crud $crud)
    {
        $object = json_decode($request->getContent());
        $id = $object->id;
        $crud->remove($id, $entity);
        return new Response($crud->getMessage(), Response::HTTP_OK, array('type-message' => $crud->getTypeMessage()));
    }
}
