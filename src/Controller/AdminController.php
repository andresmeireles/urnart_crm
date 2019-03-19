<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Model\UserModel;
use App\Entity\User;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;

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
     * @Route("/admin/user", name="user_page")
     */
    public function viewUserPage()
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Você não pode acessar está pagina.');

        return $this->render('admin/pages/user/index.html.twig');
    }

    /**
     * @Route("/admin/user/permission", methods="GET", name="user_permission")
     */
    public function viewUserPermission(): Response
    {
         return $this->render('admin/pages/user/userPermission.html.twig', array(
             'users' => $this->getDoctrine()->getRepository(User::class)->findAll()
         ));
    }

    /**
     * @Route("/admin/user/permission", methods="POST", name="user_permission_async")
     */
    public function editUserPermissions(Request $request, UserModel $model, bool $asynchronous = false): Response
    {
        if ($this->isCsrfTokenValid('permissions', $request->request->get('_csrf_token'))) {
            throw new InvalidCsrfTokenException('Token incorreto ou invalido');    
        }   

        $userId = $request->request->get('identification');
        $userName = $request->request->get('name');
        $roles = $request->request->get('user_roles') ?? array();

        $response = $model->editRoles($userId, $userName, $roles);

        if ($asynchronous) {
            return new Response($response->getMessage(), 200, array('type' => $response->getType()));
        }

        $this->addFlash($response->getType(), $response->getMessage());

        return $this->redirectToRoute('user_permission');
    }

    /** @Route("/api/admin/permission", methods="POST") */
    public function asyncEditUserPermissions(Request $request, UserModel $model): Response
    {
        return $this->editUserPermissions($request, $model, true);
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
