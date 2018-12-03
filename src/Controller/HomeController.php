<?php
/**
 * HomeController
 *
 * @category Controller
 * @package  App\Controller
 * @author   André Meireles <andre2meireles@gmail.com>
 * @license  MIT <https://mit-license.org>
 * @link     https://bitbucket.org/andresmeireles/sysadmin
 */
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Bridge\Twig\Extension\CsrfRuntime;

/**
 * Classe do controller home
 *
 * @category Contoller
 * @package  App\Controller\HomeController
 * @author   André Meireles <andre2meireles@gmail.com>
 * @license  MIT <https://mit-license.org>
 * @link     https://bitbucket.org/andresmeireles/sysadmin
 */
class HomeController extends Controller
{
    /**
     * Move para a primeira pagina
     *
     * @Route("/home", name="home")
     * @Route("/",     name="index")
     *
     * @return Response
     */
    public function index()
    {
        dump($this->get('session')->get('csrftoken')->getId(), $this->get('session')->get('csrftoken')->getValue(), $this->isCsrfTokenValid($this->get('session')->get('csrftoken')->getId(), $this->get('session')->get('csrftoken')),
        password_verify($this->get('session')->get('csrftoken')->getId(), $this->get('session')->get('csrftoken')));
        return $this->render('home/index.html.twig');
    }

    /**
     * hot reload csrf token - test
     *
     * @Route("/changeCsrf")
     * 
     * @return Response
     */
    public function changeCsrf()
    {
        $ctm = new CsrfTokenManager();
        $newToken = new CsrfRuntime($ctm);
        $token = $ctm->getToken((new \DateTime('now'))->format('d-m-Y H:m:s'));
        $this->get('session')->set('csrftoken', $token);
        return new Response(json_encode(array('st' => $token->getValue(), 'cst' => $newToken->getCsrfToken($token->getId()))));
    }

    /**
     * Redireciona para pagina de objetivos a alcançar
     *
     * @Route("/activiment")
     *
     * @return Response
     */
    public function activiment(): Response
    {
        return $this->render('home/activiments.html.twig');
    }
}
