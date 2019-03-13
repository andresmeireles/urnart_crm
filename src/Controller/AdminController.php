<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/admin/edit", name="useredit", methods="GET") 
     */
    public function viewEdit(): Response
    {
        return $this->render('admin/index.html.twig', [
            'edit' => true
        ]);
    }

    /**
     * @Route("/admin/edit", methods="POST")
     */
    public function editUser(Request $request)
    {
        dump($request->request->all());
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
