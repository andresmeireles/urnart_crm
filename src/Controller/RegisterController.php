<?php

namespace App\Controller;

use App\Config\NonStaticConfig;
use App\Utils\Exceptions\CustomException;
use App\Utils\Generic\Crud;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;
use App\Model\ModelsModel;

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
     * @Route("/register/add/model", methods={"POST", "PUT"})
     *
     * @param Request $request
     * @return Response
     */
    public function addModelRegister(Request $request, ModelsModel $modelsModel): Response
    {
        $name = $request->request->get('name');
        $height = $request->request->get('height');
        $specificity = $request->request->get('specificity') ?? null;
        $colors = $request->request->get('colors') ?? null;
        $suggestedPrice = $request->request->get('suggestedPrice');
        $insertResult = $modelsModel->insertModel($name, $height, $suggestedPrice, $specificity, $colors);
        $this->addFlash($insertResult->getType(), $insertResult->getMessage());

        return $this->redirectToRoute('register');
    }

    /**
     * @Route("/register/add/{entity}", methods={"POST", "PUT"})
     */
    public function addGenericRegister(string $entity, Crud $setter, Request $request)
    {
        $setter->set($entity, $request->request->all());
        $this->addFlash('success', 'Item adicionado com sucesso.');

        return $this->redirectToRoute('register');
    }
    
    /**
     * @Route("api/register/add/{entity}", methods={"POST", "PUT"})
     */
    public function addGenericRegisterAjax(string $entity, Crud $setter, Request $request)
    {
        $setter->set($entity, $request->request->all());

        return new Response('Produto adicionado com sucesso', 200, [
            'type-message' => 'success'
        ]);
    }

    /**
     * @Route("/register/get/{entity}", methods={"POST", "PATCH"})
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
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Algo deu muito errado :(');
        }

        $criteria = (array) json_decode($request->getContent());
        $result = $getter->getWithSimpleCriteriaJson($entity, $criteria);
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
     * @Route("/register/remove/{entity}", methods={"DELETE"})
     */
    public function getGenericRemover(string $entity, Request $request, Crud $crud)
    {
        $id = json_decode($request->getContent())->id;
        $response = $crud->remove($id, $entity);

        return new Response(
            $response->getMessage(),
            Response::HTTP_OK,
            ['type-message' => $response->getType()]
        );
    }

    /**
     * @Route("/register/configuration", methods="GET", name="config")
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
     */
    public function writeConfiguration(Request $request): Response
    {
        if (!$this->isCsrfTokenValid('configuration', $request->request->get('_csrf_token'))) {
            throw new CustomException('Token incorreto ou não enviado.');
        }

        return $this->redirectToRoute('config');
    }

    /**
     * @Route("/resetlogo", methods="PUT")
     */
    public function resetlogo(Request $request): Response
    {
        $hash = json_decode($request->getContent())->hash;
        if (!($hash == hash('ripemd160', 'valido'))) {
            return new Response('false', 400);
        }
        // $config->resetLogoImage();
        return new Response('true', 200);
    }
}
