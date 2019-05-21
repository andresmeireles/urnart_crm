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

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Model\ListModel;
use App\Entity\ManualOrderReport;

/**
 * Classe do controller home
 *
 * @category Contoller
 * @package  App\Controller\HomeController
 * @author   André Meireles <andre2meireles@gmail.com>
 * @license  MIT <https://mit-license.org>
 * @link     https://bitbucket.org/andresmeireles/sysadmin
 */
class HomeController extends AbstractController
{
    /**
     * Move para a primeira pagina
     *
     * @Route("/home", name="home")
     * @Route("/",     name="index")
     *
     * @return Response
     */
    public function index(ListModel $model)
    {
        //chart
        $boletoValue = 0.0;
        $boletoData = $model->dqlConsult('SELECT u.boletoValue FROM App\Entity\Boleto u');
        $boletoAmount = count($boletoData);
        foreach ($boletoData as $key) {
            $boletoValue += $key['boletoValue'];
        }

        $orderValue = 0.0;
        $orderData = $this->getDoctrine()->getRepository(ManualOrderReport::class)->findAll();
        $orderAmount = count($orderData);
        /** @var ManualOrderReport $key */
        foreach ($orderData as $key) {
            $orderValue += $key->getOrderFinalPrice();
        }

        return $this->render('home/index.html.twig', [
            'values' => array($boletoValue, $orderValue),
            'amount' => array($boletoAmount, $orderAmount)
        ]);
    }

    /**
    * @Route("/financeiro")
    *
    * @return Response  Symofny response object.
    */
    public function underConstruct(): Response
    {
        return new Response('Está pagina ainda está em contrução. Por favor volte a pagina.');
    }

    /**
     * Simple greeting
     *
     * @Route("/greeting/{name}", defaults={"name"=null})
     *
     * @param string|null $name
     * @return Response
     */
    public function greeting(?string $name = 'meu amigo'): Response
    {
        return new Response(sprintf('Olá %s', $name));
    }

    /**
     * @Route("/test")
     */
    public function testPage()
    {
        return $this->render('newpagesidebar.html.twig');
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
