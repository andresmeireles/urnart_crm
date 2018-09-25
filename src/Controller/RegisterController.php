<?php

namespace App\Controller;

use App\Utils\Generic\GenericSetter;
use App\Utils\Generic\Crud;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Yaml\Yaml;

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
            'customer' => $getter->get('pessoaJuridica'),
            'payType' => $getter->get('paymentType'),
            'transporters' => $getter->get('transporter'),
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
     * @Route("/register/get/criteria/{entity}", methods="POST")
     */
    public function getRegisterWithSimpleCriteria(string $entity, Request $request, Crud $getter)
    {
        $criteria = (array) json_decode($request->getContent());
        $result = $getter->getWithSimpleCriteriaJson($entity, $criteria);
        $var = json_encode($result);
        return new Response($result, Response::HTTP_OK);
    }

    /**
     * @Route("/register/get", methods="GET")
     */
    public function getSingleRegiterById(Request $request, Crud $getter)
    {
        $entity = $request->query->get('entity') ?? $request->request->get('entity');
        $id = $request->query->get('id') ?? $request->request->get('id');
        $result = $getter->getRegisterById($entity, $id);
        return new Response($result);
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

    /**
     * @Route("/register/configuration", methods="GET", name="config")
     * 
     * @return Response
     */
    public function configuration(): Response
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/../Config/system-config.yaml'));
        return $this->render('/register/configuration/index.html.twig', [
            'config' => $config
        ]);
    }

    /**
     * @Route("/register/configuration", methods="POST")
     *
     * @param Request
     * @return Response : write new config
     */
    public function writeConfiguration(Request $request): Response
    { 
        $config = Yaml::parse(file_get_contents(__DIR__.'/../Config/system-config.yaml'));
        $configSendData = $request->request->all();
        
        foreach ($config as $key => $value) {
            if (array_key_exists($key, $configSendData)) {
                $config[$key] = ($configSendData[$key] == 'on' ? true : false);
                continue;
            }
            $config[$key] = false;
        }

        $yaml = Yaml::dump($config);
        file_put_contents(__DIR__.'/../Config/system-config.yaml', $yaml);

        $this->addFlash(
            'success',
            'configuração salva com sucesso'
        );

        return $this->redirectToRoute('config');
    }
}
