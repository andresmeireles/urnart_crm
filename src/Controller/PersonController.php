<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use App\Entity\Proprietario;
use App\Entity\Email;
use App\Entity\Phone;
use App\Entity\PessoaFisica;
use App\Entity\PessoaJuridica;
use App\Utils\Generic\Crud;

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
     * @Route("/person/customer", name="customer")
     */
    public function showCustomer(Crud $getter)
    {
        return $this->render('person/pages/customer.html.twig', [
            'person' => $getter->get('pessoaJuridica'),
            'estado' => $getter->get('estado')
        ]);
    }

    /**
     * @Route("/person/person")
     * 
     * Pagina apenas para visualização de informações
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
        
        //start transation 
        $em->getConnection()->beginTransaction();

        try {
            $persistData($request->request->all());

            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw new \Exception($e->getMessage().'. Arquivo '. $e->getFile() .' linha '. $e->getLine());
        }
        return new Response();
    }

    public function persistData(array $data): void
    {
        extract($data);

        $em = $this->getDoctrine()->getManager();
        $persona = new PessoaFisica();

        $persona->setFirstName($person['firstName']);
        $persona->setLastName($person['lastName']);
        $persona->setCpf($person['cpf']);
        $persona->setRg($person['rg']);
        $g = $person['genre'] ?? null;
        $persona->setGenre($g);
        $persona->setBirthDate(new \DateTime(str_replace('/', '.', $person['birthDate'])));
        
        foreach ($phone as $phones) {
            $telephone = new Phone;
            $phones = str_replace(' ', '', str_replace('(', '', str_replace(')', '', str_replace('-', '', $phones))));
            $telephone->setNumber($phones);
            $persona->addPhone($telephone);
        }

        foreach ($email as $emails) {
            $mail = new Email;
            $emails = str_replace('(', '', str_replace(')', '', str_replace('-', '', $emails)));
            $mail->setEmail($emails);
            $persona->addEmail($mail);
        }

        $em->persist($persona);

        $proprietary = new Proprietario;
        $proprietary->setPessoaFisica($persona);

        $em->persist($proprietary);

        $client->setRazaoSocial();
        $client->setNomeFantasia();
        $client->setCnpj();
        $client->setInscricaoEstadual();
        $client->setDataDeFundação(new \DateTime(str_replace('/', '.', $customer['foundation'])), new \DateTimeZone('America/Sao_Paulo'));
        $client->addProprietario($proprietary);

        $em->persist($client);
    }

    public function persistCustomer(array $customer): void
    {
        $em = $this->getDoctrine()->getManager();
        $client = new PessoaJuridica();

       
    }

    public function persistPhone(Request $request)
    {}

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