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
        return $this->render(
            'home/index.html.twig',
            [
            'controller_name' => 'HomeController',
            ]
        );
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
