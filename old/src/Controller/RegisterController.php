<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ModelName;
use App\Model\ModelsModel;
use App\Utils\Exceptions\CustomException;
use App\Utils\Generic\Crud;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function index(): Response
    {
        return $this->render('register/index.html.twig');
    }

    /**
     * @Route("/register/add/model", methods={"POST", "PUT"})
     *
     * @throws \Exception
     */
    public function addModelRegister(Request $request, ModelsModel $modelsModel): Response
    {
        $name = $request->request->get('name');
        $height = $request->request->get('height');
        $specificity = $request->request->get('specificity') ?? null;
        $colors = $request->request->get('colors') ?? null;
        $suggestedPrice = $request->request->get('suggestedPrice');
        $insertResult = $modelsModel->insertModel($name, $height, (float) $suggestedPrice, $specificity, $colors);
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
            'type-message' => 'success',
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
     * @Route("/register/get", methods={"GET", "POST"}, name="get_registry")
     */
    public function getSingleRegiterById(Request $request, Crud $getter)
    {
        $entity = $request->query->get('entity') ??
            $request->request->get('entity') ??
            json_decode($request->getContent())->entity;
        $registryId = $request->query->get('id') ??
            $request->request->get('id') ??
            json_decode($request->getContent())->id;
        $result = $getter->getRegisterById($entity, (int) $registryId);
        dump($result);
        die;

        //return n($result);
    }

    /**
     * @Route("/register/getProduct/price", methods={"POST"}, name="get_registry")
     */
    public function getProductPriceById(Request $request, Crud $getter): Response
    {
        $entity = $request->query->get('entity') ??
            $request->request->get('entity') ??
            json_decode($request->getContent())->entity;
        $registryId = $request->query->get('id') ??
            $request->request->get('id') ??
            json_decode($request->getContent())->id;
        /** @var ModelName */
        $result = $getter->getRegisterById($entity, (int) $registryId);

        return new Response((string) $result->getSuggestedPrice());
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
}
