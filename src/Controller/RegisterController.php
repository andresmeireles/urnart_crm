<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Utils\Generic\GenericSetter;
use App\Utils\Generic\Crud;

class RegisterController extends Controller
{
    /**
     * @Route("/register", name="register")
     */
    public function index(Crud $getter)
    {
        return $this->render('register/index.html.twig', [
            'departament' => $getter->get('departament'),
            'unit' => $getter->get('unit'),
            'state' => $getter->get('estado'),
            'city' => $getter->get('municipio'),
        ]);
    }

    /**
     * @Route("/register/add/{entity}", methods="POST")
     */
    public function addGenericRegister(string $entity, Crud $setter, Request $request)
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
    public function getGenericRegister(string $entity, Crud $getter)
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
        return new Response(
            $crud->getMessage(),
            Response::HTTP_OK,
            array('type-message' => $crud->getTypeMessage())
        );
    }
}
