<?php
namespace App\Controller;

use App\Entity\Email;
use App\Entity\Estado;
use App\Entity\Municipio;
use App\Entity\PessoaJuridica;
use App\Entity\Phone;
use App\Entity\Proprietario;
use App\Model\PersonModel;
use App\Utils\Exceptions\CustomException;
use App\Utils\Generic\Crud;
use Respect\Validation\Validator as v;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
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
            'estado' => $getter->get('estado'),
            'municipios' => $this->getDoctrine()->getManager()->getRepository(Municipio::class)->findAll(),
        ]);
    }

    /**
     * @Route("/person/person", name="person")
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
     * @param PersonModel $model
     * @param Request $request
     * @return Response
     * @throws \Exception
     */
    public function persist(PersonModel $model, Request $request)
    {
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Algo deu muito errado :(');
        }
        
        $data = $request->request->all();
        $result = $model->persist($data);
        $this->addFlash(
            $result['type'],
            $result['message']
        );
        return $this->redirectToRoute('customer');
    }

    /**
     * @Route("/person/action/{id}", methods={"DELETE", "POST"}, defaults={"id"=""})
     */
    public function action($personId, Request $request): Response
    {
        if (!$this->isCsrfTokenValid('autenticateBoleto', $request->request->get('_csrf_token'))) {
            throw new CustomException('Algo deu muito errado :(');
        }
        
        $entityManager = $this->getDoctrine()->getManager();

        //start transaction
        $$entityManager->getConnection()->beginTransaction();

        try {
            $customer = $entityManager->getRepository(PessoaJuridica::class)->find($personId);
            if ($request->server->get('REQUEST_METHOD') == 'POST') {
                $updateData = $request->request->all();
                $this->updateCustomerData($customer, $updateData);
            } elseif ($request->server->get('REQUEST_METHOD') == 'DELETE') {
                $this->removeAllCustomerData($customer);
            } else {
                throw $this->createAccessDeniedException('Ação não pode ser concluida');
            }
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (\Exception $e) {
            $entityManager->getConnection()->rollback();
            $this->addFlash(
                'error',
                'Cliente possui um pedido cadastrado em seu nome, por isso não pode ser removido'
            );
            //throw new \Exception($e->getMessage() . '. Arquivo ' . $e->getFile() . ' linha ' . $e->getLine());
        }

        return $this->redirectToRoute('person');
    }

    public function removeAllCustomerData(PessoaJuridica $pessoa): void
    {
        $entityManager = $this->getDoctrine()->getManager();

        //dados do usuario
        $pessoaFisica = $pessoa->getProprietarios()->first()->getPessoaFisica();
        $proprietary = $entityManager->getRepository(Proprietario::class)->findOneBy(['pessoaFisica' => $pessoaFisica->getId()]);
        $address = $pessoaFisica->getAddress();
        $emails = $pessoaFisica->getEmails();
        $phones = $pessoaFisica->getPhones();

        foreach ($phones as $phone) {
            $entityManager->remove($phone);
        }

        foreach ($emails as $email) {
            $entityManager->remove($email);
        }

        $entityManager->remove($address);
        $entityManager->remove($pessoa);
        $entityManager->remove($proprietary);
        $entityManager->remove($pessoaFisica);
    }

    public function updateCustomerData(PessoaJuridica $pessoa, array $data): void
    {
        if (!$data) {
            throw $this->createNotFoundException('Parametros não encontrados');
        }
        if (!$pessoa) {
            throw $this->createNotFoundException('Item não existe');
        }

        $entityManager = $this->getDoctrine()->getManager();
        extract($data);

        $client = $pessoa;
        $pessoaFisica = $pessoa->getProprietarios()->first()->getPessoaFisica();
        $proprietary = $entityManager->getRepository(Proprietario::class)->findOneBy(['pessoaFisica' => $pessoaFisica->getId()]);
        $addr = $pessoaFisica->getAddress();

        $cpf = v::cpf()->validate($person['cpf']) ? $person['cpf'] : false;
        $cnpj = $customer['cnpj'];

        $pessoaFisica->setFirstName($person['firstName']);
        $pessoaFisica->setLastName($person['lastName']);
        $pessoaFisica->setCpf($cpf);
        $pessoaFisica->setRg($person['rg']);
        $genre = $person['genre'] ?? null;
        $pessoaFisica->setGenre($genre);
        $date = $person['birthDate'] != '' ? new \DateTime(str_replace('/', '.', $person['birthDate'])) : null;
        $pessoaFisica->setBirthDate($date);
        
        foreach ($phone as $phones) {
            $telephone = new Phone;
            $phones = (int)str_replace(' ', '', str_replace('(', '', str_replace(')', '', str_replace('-', '', $phones))));

            $telephone->setNumber($phones);
            $pessoaFisica->addPhone($telephone);
            $entityManager->merge($telephone);
        }

        foreach ($email as $emails) {
            $mail = new Email();
            $emails = str_replace('(', '', str_replace(')', '', str_replace('-', '', $emails)));
            $mail->setEmail($emails);
            $pessoaFisica->addEmail($mail);
            $entityManager->merge($mail);
        }

        $proprietary->setPessoaFisica($pessoaFisica);

        $client->setRazaoSocial($customer['razaoSocial']);
        $client->setNomeFantasia($customer['nomeFantasia']);
        $client->setCnpj($cnpj);
        $inscricaoEstadual = $customer['inscricaoEstadual'] == '' ? null : $customer['inscricaoEstadual'];
        $client->setInscricaoEstadual($inscricaoEstadual);
        $date = $customer['fondationDate'] != '' ? new \DateTime(str_replace('/', '.', $customer['fondationDate'])) : null;
        $client->setDataDeFundacao($date);
        $client->addProprietario($proprietary);
        $situcaoCadastral = $customer['situacaoCadastral'] ?? 3;
        $client->setSituacaoCadastral($situcaoCadastral);

        $state = isset($address['estado']) ? $entityManager->getRepository(Estado::class)->find($address['estado']) : null;
        $city = isset($address['municipio']) ? $entityManager->getRepository(Municipio::class)->find($address['municipio']) : null;

        $addr->setPessoaFisicaId($pessoaFisica);
        $addr->setMunicipio($city);
        $addr->setEstado($state);
        $addr->setRoad($address['road']);
        $addr->setNeighborhood($address['neightborhood']);
        $addr->setNumber($address['number']);
        $addr->setZipcode($address['cep']);

        $pessoaFisica->setAddress($addr);
    }
}
