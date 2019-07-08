<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;

use Symfony\Component\Yaml\Yaml;

final class WriteBoletoReport
{
    /**
     * @param   \DateTimeInterface  $date
     * @param   StdResponse         $data
     */
    public function write(\DateTimeInterface $date, StdResponse $data): void
    {
        $res = [];
        $res['naoPago'] = $this->writeArray($data->naoPago ?? null);
        $res['atrasado'] = $this->writeArray($data->atrasado ?? null);
        $res['provisionado'] = $this->writeArray($data->provisionado ?? null, 'provisionado');
        $res['porConta'] = $this->writeArray($data->conta ?? null, 'conta');

        file_put_contents(__DIR__ . '/../ReportFiles/' . $date->format('d-m-Y') . '.yaml', Yaml::dump($res));
    }

    /**
     * @param array|null $titles
     * @param string $action
     * @return array
     */
    private function writeArray(?array $titles, string $action = 'normal'): array
    {
        $result = [];

        if ($titles === null || $titles === []) {
            return [];
        }

        switch ($action) {
            case 'provisionado':
                $result = $this->writeProvisionado($titles);
                break;
            case 'conta':
                $result = $this->writeConta($titles);
                break;
            default:
                $result = $this->normalAction($titles);
                break;
        }
        return $result;
    }

    /**
     * @param array $titles
     * @return array
     */
    private function normalAction(array $titles): array
    {
        $result = [];

        foreach ($titles as $key => $title) {
            $result[$key]['boletoCustomerOwner'] = $title->getBoletoCustomerOwner();
            $result[$key]['boletoStatus'] = $title->getBoletoStatus();
            $result[$key]['boletoVencimento'] = $title->getBoletoVencimento();
            $result[$key]['boletoNumber'] = $title->getBoletoNumber();
            $result[$key]['boletoInstallment'] = $title->getBoletoInstallment();
            $result[$key]['boletoValue'] = number_format($title->getBoletoValue(), 2, '.', '');
        }

        return $result;
    }

    /**
     * @param array $titles
     * @return array
     */
    private function writeProvisionado(array $titles): array
    {
        $result = [];

        foreach ($titles as $key => $title) {
            $result[$key]['boletoCustomerOwner'] = $title->getBoletoCustomerOwner();
            $result[$key]['boletoStatus'] = $title->getBoletoStatus();
            $result[$key]['boletoVencimento'] = $title->getBoletoVencimento();
            $result[$key]['boletoNumber'] = $title->getBoletoNumber();
            $result[$key]['boletoInstallment'] = $title->getBoletoInstallment();
            $result[$key]['boletoPrevisionamentoDate'] = $title->getBoletoProvisionamentoDate();
            $result[$key]['boletoValue'] = number_format($title->getBoletoValue(), 2, '.', '');
        }

        return $result;
    }

    /**
     * @param array $titles
     * @return array
     */
    private function writeConta(array $titles): array
    {
        $result = [];
        foreach ($titles as $key => $title) {
            $result[$key]['boletoCustomerOwner'] = $title->getBoletoCustomerOwner();
            $result[$key]['boletoStatus'] = $title->getBoletoStatus();
            $result[$key]['boletoVencimento'] = $title->getBoletoVencimento();
            $result[$key]['boletoNumber'] = $title->getBoletoNumber();
            $result[$key]['boletoInstallment'] = $title->getBoletoInstallment();
            $result[$key]['boletoPorContaStatus'] = $title->getBoletoPorContaStatus();
            $result[$key]['boletoValue'] = number_format($title->getBoletoValue(), 2, '.', '');
        }

        return $result;
    }
}
