<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

use App\Utils\Andresmei\StdResponse;
use Symfony\Component\Yaml\Yaml;


class WriteBoletoReport
{
    /**
     * [write description]
     *
     * @param   \DateTimeInterface  $date  [$date description]
     * @param   StdResponse         $data  [$data description]
     *
     * @return  void                    [return description]
     */
    public function write(\DateTimeInterface $date, StdResponse $data): void
    {
        $res = [];
        $res['naoPago'] = $this->writeArray($data->naoPago ?? null);
        $res['atrasado'] = $this->writeArray($data->atrasado ?? null);
        $res['provisionado'] = $this->writeArray($data->provisionado ?? null, 'provisionado');
        $res['porConta'] = $this->writeArray($data->conta ?? null, 'conta');

        file_put_contents(__DIR__.'/../ReportFiles/'.$date->format('d-m-Y').'.yaml', Yaml::dump($res));
    }

    private function writeArray(?array $titles, string $action = 'normal'): array
    {
        $result = [];

        if (is_null($titles) || empty($titles)) {
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

    private function normalAction(array $titles): array
    {
        $result = [];

        foreach ($titles as $key => $title) {
            $result[$key]['boletoCustomerOwner'] = $title->getBoletoCustomerOwner();
            $result[$key]['boletoVencimento'] = $title->getBoletoVencimento();
            $result[$key]['boletoNumber'] = $title->getBoletoNumber();
            $result[$key]['boletoInstallment'] = $title->getBoletoInstallment();
            $result[$key]['boletoStatus'] = $title->getBoletoStatus();
            $result[$key]['boletoValue'] = number_format($title->getBoletoValue(), 2, '.', '');
        }

        return $result;
    }

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

    private function writeConta(array $titles): array
    {
        $result = [];
        foreach ($titles as $key => $title) {
            $result[$key]['boletoCustomerOwner'] = $title->getBoletoCustomerOwner();
            $result[$key]['boletoStatus'] = $title->getBoletoStatus();
            $result[$key]['boletoVencimento'] = $title->getBoletoVencimento();
            $result[$key]['boletoNumber'] = $title->getBoletoNumber();
            $result[$key]['boletoInstallment'] = $title->getBoletoInstallment();
            $result[$key]['boletoPrevisionamentoDate'] = $title->getBoletoProvisionamentoDate() ?? '';
            $result[$key]['boletoPorContaValue'] = $title->getBoletoPorContaValue() ?? null;
            $result[$key]['boletoValue'] = number_format($title->getBoletoValue(), 2, '.', '');
        }

        return $result;
    }
    
}