<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Address;
use App\Entity\Estado;
use App\Entity\Municipio;
use App\Entity\PessoaJuridica;
use App\Model\Model;
use Respect\Validation\Validator as v;
use App\Entity\PessoaFisica;
use App\Entity\Phone;
use App\Entity\Email;
use App\Entity\Proprietario;

class PersonModel extends Model
{
    protected $error = false;
    protected $errorResponse = array();

    /**
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function persist(array $data): array
    {
        extract($data);
        $cpf = empty($person['cpf']) ? null : v::cpf()->validate($person['cpf']) ? $person['cpf'] : $this->error('cpf');
        
        $cnpj = v::cnpj()->validate($customer['cnpj']) ? $customer['cnpj'] : $this->error('cnpj');
        $date = empty($person['birthDate']) ? null : v::date('d/m/Y')->validate($person['birthDate']) ? new \DateTime(str_replace('/', '-', $person['birthDate'])) : $this->error('data de nascimento');
        $fundationDate = $customer['fondationDate'] !== '' ? null : v::date('d/m/Y')->validate($customer['fondationDate']) ? new \DateTime(str_replace('/', '-', $customer['fondationDate'])) : $this->error('data de fundação');

        if ($this->error) {
            dump($customer['fondationDate'] === '', v::date()->validate(\DateTime(str_replace('/', '-', $customer['fondationDate']))), $this->error, $this->errorResponse);
            die();
            return $this->errorResponse;
        }

        $em = $this->em;
        $em->getConnection()->beginTransation();

        try {
            $pessoaFisica = new PessoaFisica();
            $pessoaFisica->setFirstName($person['firstName']);
            $pessoaFisica->setLastName($person['lastName']);
            $pessoaFisica->setCpf($cpf);
            $pessoaFisica->setRg($person['rg']);
            $g = $person['genre'] ?? null;
            $pessoaFisica->setGenre($g);
            $pessoaFisica->setBirthDate($date);

            foreach ($phone as $phones) {
                $telephone = new Phone;
                $phones = (int)str_replace(' ', '', str_replace('(', '', str_replace(')', '', str_replace('-', '', $phones))));

                $telephone->setNumber($phones);
                $em->persist($telephone);
                $pessoaFisica->addPhone($telephone);
            }

            foreach ($email as $emails) {
                $mail = new Email();
                $emails = str_replace('(', '', str_replace(')', '', str_replace('-', '', $emails)));
                $mail->setEmail($emails);
                $em->persist($mail);
                $pessoaFisica->addEmail($mail);
            }

            $proprietary = new Proprietario();
            $proprietary->setPessoaFisica($pessoaFisica);

            $client = new PessoaJuridica();
            $client->setRazaoSocial($customer['razaoSocial']);
            $client->setNomeFantasia($customer['nomeFantasia']);
            $client->setCnpj($cnpj);
            $inscricaoEstadual = $customer['inscricaoEstadual'] == '' ? null : $customer['inscricaoEstadual'];
            $client->setInscricaoEstadual($inscricaoEstadual);
            $client->setDataDeFundacao($fundationDate);
            $client->addProprietario($proprietary);
            $situcaoCadastral = $customer['situacaoCadastral'] ?? 3;
            $client->setSituacaoCadastral($situcaoCadastral);

            $state = isset($address['estado']) ? $em->getRepository(Estado::class)->find($address['estado']) : null;
            $city = isset($address['municipio']) ? $em->getRepository(Municipio::class)->find($address['municipio']) : null;

            $addr = new Address();
            $addr->setPessoaFisicaId($pessoaFisica);
            $addr->setMunicipio($city);
            $addr->setEstado($state);
            $addr->setRoad($address['road']);
            $addr->setNeighborhood($address['neightborhood']);
            $addr->setNumber($address['number']);
            $addr->setZipcode($address['cep']);

            $pessoaFisica->setAddress($addr);

            $em->persist($proprietary);
            $em->persist($client);
            $em->persist($pessoaFisica);
            $em->persist($addr);
            $em->flush();
            $em->getConnection()->commit();

            return array(
                'http_code' => 200,
                'type'      => 'success',
                'message'   => 'Cliente adicionado com sucesso'
            );
        } catch(\Exception $e) {
            $em->getConnection()->rollback();
            return array(
                'http_code' => 400,
                'type'      => 'error',
                'message'   => $e->getMessage()
            );
            //throw new \Exception($e->getMessage());
        }
    }

    public function error(string $type): ?bool
    {
        $this->error = true;
        $this->errorResponse = array(
            'http_code' => 400,
            'type' => 'warning',
            'message' => "Informação em {$type} não é valida"
        );
        return false;
    }
}