<?php

namespace App\Controller;

use App\Entity\User;
use App\Model\UserModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @IsGranted("ROLE_ADMIN")
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
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Você não pode acessar está pagina.');

        return $this->render('admin/pages/user/userPermission.html.twig', array(
            'users' => $this->getDoctrine()->getRepository(User::class)->findAll()
        ));
    }

    /**
     * @Route("/admin/user/permission", methods="POST", name="user_permission_async")
     */
    public function editUserPermissions(Request $request, UserModel $model, bool $asynchronous = false): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Você não pode acessar está pagina.');

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

    /** 
     * @Route("/api/admin/permission", methods="POST") 
     */
    public function asyncEditUserPermissions(Request $request, UserModel $model): Response
    {
        return $this->editUserPermissions($request, $model, true);
    }

    /**
     * @Route("/admin/user/add", name="add_user", methods="GET")
     */
    public function viewAddUser(): Response
    {
        return $this->render('admin/pages/user/createuser.html.twig');
    }

    /**
     * @Route("/admin/user/add", name="add_post_user", methods="POST")
     */
    public function addUser(Request $request, UserModel $model, UserPasswordEncoderInterface $encoder): Response
    {
        $this->denyAccessUnlessGranted('ROLE_SUPER_ADMIN', null, 'Você não está autorizado a está aqui.');
        $this->isCsrfTokenValid('add_user', $request->request->get('_csrf_token'));
        $request->request->remove('_csrf_token');

        $file = $request->files->get('profileImage');
        $data = $request->request->all();
        $result = $model->addUser($data, $file, $encoder);

        $this->addFlash($result->getType(), $result->getMessage());

        return $this->redirectToRoute('user_page');
    }

    /**
     * ISSO SERÁ APAGADO
     * @Route("/showuser", methods="GET", name="showuser")
     */
    public function showUser(): Response
    {
        return $this->render('admin/pages/user/x.html.twig', array(
            'users' => $this->getDoctrine()->getRepository(User::class)->findAll()
        ));
    }
}
