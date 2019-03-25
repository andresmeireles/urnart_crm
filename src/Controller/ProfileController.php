<?php

namespace App\Controller;

use App\Model\UserModel;
use App\Utils\Exceptions\CustomException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

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
     * @Route("/profile/edit",name="useredit",methods="GET")
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
    public function editUser(Request $request, UserModel $model): Response
    {
        if (!$this->isCsrfTokenValid('profile_update', $request->request->get('_csrf_token'))) {
            throw new CustomException('Token incorreto ou inexistente :(');
        }

        if (null === $this->getUser()) {
            throw new CustomException('Usuario não existe');
        }

        $result = $model->editUser($request->request->all(), $request->files->get('profileImage'));

        $this->addFlash($result->getType(), $result->getMessage());

        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/profile/reset", methods="POST")
     */
    public function resetImage(UserModel $model): Response
    {
        $path = sprintf(
            '%s/public%s',
            $this->getParameter('kernel.project_dir'),
            $this->getParameter('app.path.user_profile_images')
        );

        $result = $model->resetProfileImage($path, $this->getUser());

        $this->addFlash($result->getType(), $result->getMessage());

        return $this->redirectToRoute('profile');
    }

    /**
     * @Route("/profile/password", name="change_pass", methods="GET")
     */
    public function viewPassword(Request $request): Response
    {
        return $this->render('profile/changePass.html.twig', array(
            'oldPass' => $request->query->get('oldpass'),
            'pass' => $request->query->get('pass'),
            'retype' => $request->query->get('repass')
        ));
    }

    /**
     * @Route("/profile/password", methods="POST")
     */
    public function changePassword(Request $request, UserModel $model, UserPasswordEncoderInterface $encoder): Response
    {
        $data = $request->request->all();
        $result = $model->changePassword($this->getUser(), $data, $encoder);

        $this->addFlash($result->getType(), $result->getMessage());
        $redirectRoute = $result->getType() === 'error' ? 'change_pass' : 'profile';

        return $this->redirectToRoute($redirectRoute);
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
