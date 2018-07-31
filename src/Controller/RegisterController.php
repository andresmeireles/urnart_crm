<?php

namespace App\Controller;

use App\Utils\Generic\GenericSetter;
use App\Utils\Generic\Crud;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/register/get/criteria/{entity}")
     */
    public function getRegisterWithSimpleCriteria(string $entity, Request $request, Crud $getter)
    {
        $criteria = (array) json_decode($request->getContent());
        $result = $getter->getWithSimpleCriteriaJson($entity, $criteria);
        dump($response);
        die();
        return new JsonResponse($result, Response::HTTP_OK);
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
