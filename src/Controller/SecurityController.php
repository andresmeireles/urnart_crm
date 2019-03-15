<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // redirect if user is loged
        if (!is_null($this->getUSer())) {
            $this->addFlash('warning', sprintf('%s... você já está logado truta, continue navegando em paz :)', $this->getUser()->getUserNickname()));
            return $this->redirectToRoute('index');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/user", methods={"PATCH", "POST"})
     */
    public function getUserName(): Response
    {
        /**
         * @var \App\Entity\User  $userName
         */
        $userName = $this->getUser();

        return new Response($userName->getUserNickname());
    }
}
