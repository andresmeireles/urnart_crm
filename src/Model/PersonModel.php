<?php
declare(strict_types=1);

namespace App\Model;

use App\Config\Config;
use App\Entity\Address;
use App\Entity\Email;
use App\Entity\Estado;
use App\Entity\Municipio;
use App\Entity\PessoaFisica;
use App\Entity\PessoaJuridica;
use App\Entity\Phone;
use App\Entity\Proprietario;
use App\Model\Model;
use App\Utils\Exceptions\FieldAlreadyExistsException;
use Doctrine\Common\Persistence\ObjectManager;
use Respect\Validation\Validator as v;
use Symfony\Component\Yaml\Yaml;

class PersonModel extends Model
{
    protected $error = false;
    protected $errorResponse = array();

    /**
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function persist(array $data): array
    {
        extract($data);
        //$person['cpf'] = $person['cpf'] === "" ? null : $person['cpf'];
        $person['cpf'] = v::cpf()->validate($person['cpf']) === true ? $person['cpf'] : $this->error('cpf');
        $cnpj = $customer['cnpj'] === "" ? null : $customer['cnpj'];
        if (Config::getProperty('check_cnpj')) {
            $cnpj = v::cnpj()->validate($cnpj) ? $customer['cnpj'] : $this->error('cnpj');
        }
        $person['birthDate'] = v::date()->validate(new \DateTime(str_replace('/', '-', $person['birthDate']))) == true ? $person['birthDate'] : $this->error('data de nascimento');
        $customer['foundationDate'] = v::date()->validate(new \DateTime(str_replace('/', '-', $customer['foundationDate']))) == true ? $customer['foundationDate'] : $this->error('data de fundação');
        if ($this->error) {
            return $this->errorResponse;
        }
        if (strlen($cnpj) == 14) {
            $checkCnpj = $this->checkSameField('cnpj', $cnpj);
            if ($checkCnpj['result'] !== 0) {
                throw new FieldAlreadyExistsException('Cnpj já existe');
            }
        }
        if (is_null($person['cpf'])) {
            return array(
                'http_code' => 400,
                'type' => 'warning',
                'message' => 'CPF não pode estar vázio',
            );
        }
        if (!$this->config['allow_same_cpf']) {
            $result = $this->checkSameField('cpf', $person['cpf']);
            if ($result['result'] !== 0) {
                return array(
                    'http_code' => 400,
                    'type' => 'warning',
                    'message' => 'CPF já cadastrado para algum outro cliente'
                );
            }
        }
        $person['birthDate'] = $person['birthDate'] === "" ? null : $person['birthDate'];
        $customer['foundationDate'] = $customer['foundationDate'] === "" ? null : $customer['foundationDate'];

        $em = $this->em;
        $em->getConnection()->beginTransaction();

        try {
            $pessoaFisica = new PessoaFisica();
            $pessoaFisica->setFirstName($person['firstName']);
            $pessoaFisica->setLastName($person['lastName']);
            $pessoaFisica->setCpf($person['cpf']);
            if ($this->config) {
                $pessoaFisica->setRg($person['rg']);
            }
            $g = $person['genre'] ?? null;
            $pessoaFisica->setGenre($g);
            $date = $person['birthDate'] != '' ? new \DateTime(str_replace('/', '.', $person['birthDate'])) : null;
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
            $client->setInscricaoEstadual((int) $inscricaoEstadual);
            $foundation = $customer['foundationDate'] != '' ? new \DateTime(str_replace('/', '.', $customer['foundationDate'])) : null;
            $client->setDataDeFundacao($foundation);
            $client->addProprietario($proprietary);
            $situcaoCadastral = $customer['situacaoCadastral'] === "" ? 3 : (int) $customer['situacaoCadastral'];
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
        } catch (\Exception $e) {
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

    private function checkSameField(string $field, string $value): ?array
    {
        $connection = $this->em->getConnection();
        $statement = $connection->prepare("SELECT COUNT($field) AS result FROM pessoa_fisica WHERE $field = :$field");
        $statement->bindValue($field, $value);
        $statement->execute();
        return $statement->fetch();
    }
}