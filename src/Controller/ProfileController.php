<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use App\Utils\Exceptions\CustomException;

/**
 * @IsGranted("ROLE_USER")
 */
class ProfileController extends AbstractController
{
    /**
     * @Route("/profile", name="profile")
     */
    public function index()
    {
        return $this->render('profile/index.html.twig');
    }

    /**
     * @Route("/profile/edit", name="useredit", methods="GET") 
     */
    public function viewEdit(): Response
    {
        return $this->render('profile/index.html.twig', [
            'edit' => true
        ]);
    }

    /**
     * @Route("/profile/edit", methods="POST")
     */
    public function editUser(Request $request)
    {
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Token incorreto ou inexistente :(');
        }
        
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
