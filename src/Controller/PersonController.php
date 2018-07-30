<?php
namespace App\Controller;

use App\Utils\Generic\Crud;
use App\Entity\PessoaFisica;
use App\Entity\PessoaJuridica;
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
        $em = $this->getDoctrine()->getManager();
        extract($request->request->all());
        
        dump($request->request->all(), $person);
        die();

        //start transation 
        $em->getConnection()->beginTransaction();

        try {
            if ($person) {
                $this->persistPerson($person);
            }

            if ($customer) {
                $this->persistCustomer($customer);
            }

            if ($address) {
                $this->persistAddress($address);
            }

            if ($phone) {
                $this->persistPhone($phone);
            }

            if ($email) {
                $this->persistEmail($email);
            }

            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw $e->getMessage();
        }
    }

    public function persistPerson(array $person)
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