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
        dump($request->request->all());
        die(); 

        $em = $this->getDoctrine()->getManager();
        
        //start transation 
        $em->getConnection()->beginTransaction();

        try {
            $this->persistPerson($request->request->all());
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw new \Exception($e->getMessage().'. Arquivo - '. $e->getFile() .' Linha '. $e->getLine());
        }

        return new Response('sucesso!');
    }

    /**
     * @Route("/person/action/{id}", methods={"DELETE", "PUT"}, defaults={"id"=""})
     */
    public function action($id, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
//        dump($request->getContent());
  //      die();

        //start transaction 
        $em->getConnection()->beginTransaction();

        try {
            $customer = $em->getRepository(PessoaJuridica::class)->find($id);
            if ($request->server->get('REQUEST_METHOD') == 'PUT') {
                $this->updateCustomerData($customer);
            } elseif ($request->server->get('REQUEST_METHOD') == 'DELETE') {
                $this->removeAllCustomerData($customer);
            } else {
                return $this->createAccessDeniedException('Ação não pode ser concluida');
            }
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw new \Exception($e->getMessage() . '. Arquivo ' . $e->getFile() . ' linha ' . $e->getLine());
        }

        return $this->redirectToRoute('customer'); 
    }

    public function persistPerson(array $data): void
    {
        extract($data);

        $em = $this->getDoctrine()->getManager();
        $PessoaFisica = new PessoaFisica();

        $PessoaFisica->setFirstName($person['firstName']);
        $PessoaFisica->setLastName($person['lastName']);
        $PessoaFisica->setCpf($person['cpf']);
        $PessoaFisica->setRg($person['rg']);
        $g = $person['genre'] ?? null;
        $PessoaFisica->setGenre($g);
        $date = $person['birthDate'] != '' ? new \DateTime(str_replace('/', '.', $person['birthDate'])) : null;
        $PessoaFisica->setBirthDate($date);
        
        foreach ($phone as $phones) {
            $telephone = new Phone();
            $phones = (int) str_replace(' ', '', str_replace('(', '', str_replace(')', '', str_replace('-', '', $phones))));
            
            $telephone->setNumber($phones);
            $em->persist($telephone);
            $PessoaFisica->addPhone($telephone);
        }

        foreach ($email as $emails) {
            $mail = new Email();
            $emails = str_replace('(', '', str_replace(')', '', str_replace('-', '', $emails)));
            $mail->setEmail($emails);
            $em->persist($mail);
            $PessoaFisica->addEmail($mail);
        }

        $proprietary = new Proprietario();
        $proprietary->setPessoaFisica($PessoaFisica);

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
        $addr->setPessoaFisicaId($PessoaFisica);
        $addr->setMunicipio($city);
        $addr->setEstado($state);
        $addr->setRoad($address['road']);
        $addr->setNeighborhood($address['neightborhood']);
        $addr->setNumber($address['number']);
        $addr->setZipcode($address['cep']);
        
        $PessoaFisica->setAddress($addr);

        $em->persist($proprietary);
        $em->persist($client);
        $em->persist($PessoaFisica);
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

    public function updateCustomerData(PessoaJuridica $pessoa, array $data) : void
    {
        if (!$data) {
            throw $this->createNotFoundException('Parametros não encontrados');
        }

        if (!$pessoa) {
            throw $this->createNotFoundException('Item não existe');
        }

        $em = $this->getDoctrine()->getManager();
        extract($data);

        $pessoaFisica = $pessoa->getProprietarios()->first()->getPessoaFisica();
        $proprietary = $em->getRepository(Proprietario::class)->findOneBy(array('pessoaFisica' => $pessoaFisica->getId()));
        $address = $pessoaFisica->getAddress();
        $telephone = $pessoaFisica->getPhones();
        $mail = $pessoaFisica->getEmails();

        $PessoaFisica->setFirstName($person['firstName']);
        $PessoaFisica->setLastName($person['lastName']);
        $PessoaFisica->setCpf($person['cpf']);
        $PessoaFisica->setRg($person['rg']);
        $g = $person['genre'] ?? null;
        $PessoaFisica->setGenre($g);
        $date = $person['birthDate'] != '' ? new \DateTime(str_replace('/', '.', $person['birthDate'])) : null;
        $PessoaFisica->setBirthDate($date);

        foreach ($phone as $phones) {
            $phones = (int)str_replace(' ', '', str_replace('(', '', str_replace(')', '', str_replace('-', '', $phones))));

            $telephone->setNumber($phones);
            $PessoaFisica->addPhone($telephone);
        }

        foreach ($email as $emails) {
            $emails = str_replace('(', '', str_replace(')', '', str_replace('-', '', $emails)));
            
            $mail->setEmail($emails);
            $PessoaFisica->addEmail($mail);
        }

        $proprietary->setPessoaFisica($PessoaFisica);

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
        $city = isset($address['municipio']) ? $em->getRepository(Municipio::class)->find($address['municipio']) : null;

        $addr->setPessoaFisicaId($PessoaFisica);
        $addr->setMunicipio($city);
        $addr->setEstado($state);
        $addr->setRoad($address['road']);
        $addr->setNeighborhood($address['neightborhood']);
        $addr->setNumber($address['number']);
        $addr->setZipcode($address['cep']);

        $PessoaFisica->setAddress($addr);
    }
}