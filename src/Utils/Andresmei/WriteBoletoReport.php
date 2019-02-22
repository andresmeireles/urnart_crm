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
                $result[] = $this->writeProvisionado($titles);
                break;
            case 'conta':
                $result[] = $this->writeConta($titles);
                break;
            default:
                $result[] = $this->normalAction($titles);
                break;
        }
        return $result;
    }

    private function normalAction(array $titles): array
    {
        $result = [];

        foreach ($titles as $title) {
            $result['id'] = $title->getId();
            $result['boletoCustomerOwner'] = $title->getBoletoCustomerOwner();
            $result['boletoStatus'] = $title->getBoletoStatus();
            $result['boletoValue'] = number_format($title->getBoletoValue(), 2, '.', '');
        }

        return $result;
    }

    private function writeProvisionado(array $titles): array
    {
        $result = [];

        foreach ($titles as $title) {
            $result['id'] = $title->getId();
            $result['boletoCustomerOwner'] = $title->getBoletoCustomerOwner();
            $result['boletoStatus'] = $title->getBoletoStatus();
            $result['boletoPrevisionamentoDate'] = $title->getBoletoProvisionamentoDate();
            $result['boletoValue'] = number_format($title->getBoletoValue(), 2, '.', '');
        }

        return $result;
    }

    private function writeConta(array $titles): array
    {
        $result = [];
        foreach ($titles as $title) {
            $result['id'] = $title->getId();
            $result['boletoCustomerOwner'] = $title->getBoletoCustomerOwner();
            $result['boletoStatus'] = $title->getBoletoStatus();
            $result['boletoPrevisionamentoDate'] = $title->getBoletoProvisionamentoDate() ?? '';
            $result['boletoPorContaValue'] = $title->getBoletoPorContaValue() ?? null;
            $result['boletoValue'] = number_format($title->getBoletoValue(), 2, '.', '');
        }

        return $result;
    }
    
}