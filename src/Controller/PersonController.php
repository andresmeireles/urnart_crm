<?php
namespace App\Controller;

use App\Utils\Generic\Crud;
use App\Entity\PessoaFisica;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PersonController extends Controller
{
    /**
     * @Route("/person") 
     */
    public function index() 
    {
        return $this->render('person/index.html.twig');
    }

    /**
     * @Route("/person/customer")
     */
    public function showCustomer(Crud $getter)
    {
        return $this->render('person/pages/customer.html.twig', [
            'person' => $getter->get('pessoaJuridica'),
        ]);
    }

    /**
     * @Route("/person/person")
     * 
     * Essa pagina vai ser destruida no futuro
     */
    public function showPerson(Crud $getter)
    {
        return $this->render('person/pages/person.html.twig', [
            'person' => $getter->get('pessoaFisica'),
        ]);
    }

    /**
     * @Route("/person/add/person", methods="POST")
     */
    public function persist(Request $request)
    {
        extract($request->request->all());
        dump($request->request->all(), $person);
        die();

        $this->persistPerson($person);
        $this->persistCustomer($customer);
        $this->persistPhone($phone);
        $this->persistEmail($email);
        $this->persistAddress($address);
    }

    public function persistPerson(Request $request)
    {
    }

    public function persistCustomer()
    {}

    public function persistPhone(Request $request)
    {
        dump($request->request);
        die();
    }

    public function perisistEmail(Request $request)
    {
        dump($request->request);
        die();
    }

    public function persistAddress(Request $request)
    {
        dump($request->request);
        die();
    }

    public function showUsers()
    {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository(PessoaFisica::class)->findAll();
    }
}