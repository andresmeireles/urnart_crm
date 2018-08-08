<?php
namespace App\Controller;

use App\Entity\Email;
use App\Entity\Phone;
use App\Entity\Estado;
use App\Entity\Address;
use App\Entity\Municipio;
use App\Utils\Generic\Crud;
use App\Entity\PessoaFisica;
use App\Entity\Proprietario;
use App\Entity\PessoaJuridica;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
            $this->persistPerson($request->request->all());
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw new \Exception($e->getMessage().'. Arquivo '. $e->getFile() .' linha '. $e->getLine());
        }

        return new Response('sucesso!');
    }

    /**
     * @Route("/person/remove/{id}", methods="GET")
     */
    public function remove($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        //Reposotories

        //start transaction 
        $em->getConnection()->beginTransaction();

        try {
            $customer = $em->getRepository(PessoaJuridica::class)->find($id);
            $this->removeAllCustomerData($customer);
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw new \Exception($e->getMessage() . '. Arquivo ' . $e->getFile() . ' linha ' . $e->getLine());
        }

        return new Response('pega paê');
        
    }

    public function persistPerson(array $data): void
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
        $date = $person['birthDate'] != '' ? new \DateTime(str_replace('/', '.', $person['birthDate'])) : null;
        $persona->setBirthDate($date);
        
        foreach ($phone as $phones) {
            $telephone = new Phone();
            $phones = (int) str_replace(' ', '', str_replace('(', '', str_replace(')', '', str_replace('-', '', $phones))));
            
            $telephone->setNumber($phones);
            $em->persist($telephone);
            $persona->addPhone($telephone);
        }

        foreach ($email as $emails) {
            $mail = new Email();
            $emails = str_replace('(', '', str_replace(')', '', str_replace('-', '', $emails)));
            $mail->setEmail($emails);
            $em->persist($mail);
            $persona->addEmail($mail);
        }

        $proprietary = new Proprietario();
        $proprietary->setPessoaFisica($persona);

        $client = new PessoaJuridica();
        $client->setRazaoSocial($customer['razaoSocial']);
        $client->setNomeFantasia($customer['nomeFantasia']);
        $client->setCnpj($customer['cnpj']);
        $inscricaoEstadual = $customer['inscricaoEstadual'] == '' ? null : $customer['inscricaoEstadual'];
        $client->setInscricaoEstadual($inscricaoEstadual);
        $date = $customer['fondationDate'] != '' ? new \DateTime(str_replace('/', '.', $customer['fondationDate'])) : null;
        $client->setDataDeFundacao($date);
        $client->addProprietario($proprietary);
        $situcaoCadastral = $customer['situacaoCadastral'] ?? 3;
        $client->setSituacaoCadastral($situcaoCadastral);

        $state = isset($address['estado']) ? $em->getRepository(Estado::class)->find($address['estado']) : null;
        $city = isset($address['municipio']) ?  $em->getRepository(Municipio::class)->find($address['municipio']) : null;

        $addr = new Address();
        $addr->setPessoaFisicaId($persona);
        $addr->setMunicipio($city);
        $addr->setEstado($state);
        $addr->setRoad($address['road']);
        $addr->setNeighborhood($address['neightborhood']);
        $addr->setNumber($address['number']);
        $addr->setZipcode($address['cep']);
        
        $persona->setAddress($addr);

        $em->persist($proprietary);
        $em->persist($client);
        $em->persist($persona);
        $em->persist($addr);
    }

    public function removeAllCustomerData(PessoaJuridica $pessoa):void 
    {
        $em = $this->getDoctrine()->getManager();

        //dados do usuario
        $pessoaFisica = $pessoa->getProprietarios()->first()->getPessoaFisica();
        $proprietary = $em->getRepository(Proprietario::class)->findOneBy(array('pessoaFisica' => $pessoaFisica->getId()));
        $address = $pessoaFisica->getAddress();
        $phones = $pessoaFisica->getPhones();
        $emails = $pessoaFisica->getEmails();

        foreach ($phones as $phone) {
            $em->remove($phone);
        }

        foreach ($emails as $email) {
            $em->remove($email);
        }

        $em->remove($address);
        $em->remove($pessoa);
        $em->remove($proprietary);
        $em->remove($pessoaFisica);
    }
}