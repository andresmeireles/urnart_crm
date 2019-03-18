<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Utils\Exceptions\CustomException;
use App\Entity\User;

/**
 * @IsGranted("ROLE_USER")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        $user = $this->getDoctrine()->getRepository(User::class)->findAll();
        return $this->render('admin/index.html.twig', [
            'users' => $user
        ]);
    }

    /**
     * @Route("/api/admin/permission", methods="POST")
     */
    public function editPermissions(Request $request): Response
    {
        dump($request);
        die();
    }

    /**
     * @Route("/adminOverload")
     * 
     * PAGINA QUE SO EXISTE PARA TESTES ISSO SERÁ APAGADO NO FUTURO.
     */
    public function overload()
    {
        /** @var \App\Entity\User */
        $user = $this->getUser();
        $this->denyAccessUnlessGranted("ROLE_ADMIN", null, sprintf('%s não está autorizado a acessar pagina requerida.', $user->getEmail()));
        return new Response('Hellow');
    }
}
