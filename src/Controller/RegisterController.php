<?php

namespace App\Controller;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Utils\Generic\Crud;
use App\Utils\Exceptions\CustomException;
use App\Config\Configuration;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     * 
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('register/index.html.twig');
    }

    /**
     * @Route("/register/add/{entity}", methods={"POST", "PUT"})
     *
     * @param string $entity
     * @param Crud $setter
     * @param Request $request
     * @return Response
     */
    public function addGenericRegister(string $entity, Crud $setter, Request $request)
    {
        $setter->set($entity, $request->request->all());
        
        $this->addFlash('success', 'Item adicionado com sucesso.');

        return $this->redirectToRoute('register');
    }
    
    /**
     * @Route("api/register/add/{entity}", methods={"POST", "PUT"})
     *
     * @param string $entity
     * @param Crud $setter
     * @param Request $request
     * @return Response
     */
    public function addGenericRegisterAjax(string $entity, Crud $setter, Request $request)
    {
        $setter->set($entity, $request->request->all());
        return new Response('Produto adicionado com sucesso', 200, array(
            'type-message' => 'success'
        ));
    }

    /**
     * @Route("/register/get/{entity}", methods={"POST", "PATCH"})
     *
     * @param string $entity
     * @param Crud $getter
     * @return Response
     */
    public function getGenericRegister(string $entity, Crud $getter)
    {
        $table = $getter->getJsonData($entity);
        return new Response($table);
    }

    /**
     * @Route("/register/get/criteria/{entity}", methods="POST")
     *
     * @param string $entity
     * @param Request $request
     * @param Crud $getter
     * @return Response
     */
    public function getRegisterWithSimpleCriteria(string $entity, Request $request, Crud $getter)
    {
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Algo deu muito errado :(');
        }

        $criteria = (array) json_decode($request->getContent());
        $result = $getter->getWithSimpleCriteriaJson($entity, $criteria);
        return new Response($result, Response::HTTP_OK);
    }

    /**
     * @Route("/register/get", methods="GET")
     *
     * @param Request $request
     * @param Crud $getter
     * @return Response
     */
    public function getSingleRegiterById(Request $request, Crud $getter)
    {
        $entity = $request->query->get('entity') ?? $request->request->get('entity');
        $id = $request->query->get('id') ?? $request->request->get('id');
        $result = $getter->getRegisterById($entity, $id);
        return new Response($result);
    }

    /**
     * @Route("/register/remove/{entity}", methods={"DELETE"})
     *
     * @param string $entity
     * @param Request $request
     * @param Crud $crud
     * @return Response
     */
    public function getGenericRemover(string $entity, Request $request, Crud $crud)
    {
        $id = (json_decode($request->getContent()))->id;
        $response = $crud->remove($id, $entity);

        return new Response(
            $response->getMessage(),
            Response::HTTP_OK,
            array('type-message' => $response->getType())
        );
    }

    /**
     * @Route("/register/configuration", methods="GET", name="config")
     *
     * @return Response
     */
    public function configuration(): Response
    {
        //$this->denyAccessUnlessGranted('ROLE_ADMIN', null, 'Usuario não autorizado a acessar pagina');

        $config = Yaml::parse((string) file_get_contents(__DIR__.'/../Config/system-config.yaml'));
        $draw = Yaml::parse((string) file_get_contents(__DIR__.'/../Config/draw-form.yaml'));
        return $this->render('/register/configuration/index.html.twig', [
            'config' => $config,
            'draw' => $draw
        ]);
    }

    /**
     * @Route("/register/configuration", methods="POST")
     *
     * @param Request $request
     * @param Configuration $config
     * @return Response write new config
     */
    public function writeConfiguration(Request $request, Configuration $config): Response
    {
        if (!$this->isCsrfTokenValid('configuration', $request->request->get('_csrf_token'))) {
            throw new CustomException('Token incorreto ou não enviado.');
        }

        $images = $request->files->get('images');
        $result = $config->writeConfFile($request->request->all(), $images);

        $this->addFlash(
            $result->getType(),
            $result->getMessage()
        );

        return $this->redirectToRoute('config');
    }

    /**
     * @Route("/resetlogo", methods="PUT")
     *
     * @param Configuration $config
     * @return Response
     */
    public function resetlogo(Configuration $config, Request $request): Response
    {
        $hash = (json_decode($request->getContent()))->hash;
        if (!($hash == hash('ripemd160', 'valido'))) {
            return new Response('false', 400);
        }
        $config->resetLogoImage();
        return new Response('true', 200);
    }
}
