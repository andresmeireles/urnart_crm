<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * Faz operações de conversão de strings em algo desejado.
 */
class StringConvertions
{
    public function moneyToFloat(string $money): float
    {
        $commaToFloat = str_replace(',', '.', $money);
        
        return (float) $commaToFloat;
    }

    public function emptyToNull(string $emptyString): ?string
    {
        if ($emptyString === '') {
            return null;
        }
        return $emptyString;
    }

    /**
     * Transforma uma string em uma data pre-definida pelo sistema
     * 
     * @param string|null $dateString
     * @return string|null
     */
    public function strToDateString(?string $dateString): ?string 
    {
        if ($dateString === null || $dateString === '') {
            return null;
        }

        $converterTime = $this->convertStringToDate($dateString, '-');
        $date = \DateTime::createFromFormat('m-d-Y', $converterTime);

        if ($date instanceof \DateTime) {
            return $date->format('Y-m-d');
        }

        throw new \Exception(sprintf('Conversão não foi possivel.'));
    }

    /**
     * Transforma string em data formatada
     *
     * @param string $string
     * @param string $delimiter
     * @param string $type
     * @return string
     */
    public function convertStringToDate(string $string, string $delimiter, string $type = 'POR'): string
    { 
        switch (strtoupper($type)) {
            case 'POR':
                $date = explode($delimiter, $string);
                $day = (int) $date[0];
                $month = (int) $date[1];
                $year = (int) $date[2];
                if (!checkdate($month, $day, $year)) {
                    throw new BadCredentialsException(sprintf('Data informada DIA = %s, MES = %s, ANO = %s não é valida.', $day, $month, $year));
                }        
                return "{$month}-{$day}-{$year}";
                break;
            default:
                throw new \Exception(sprintf('Tipo de formação de data %s não suportdado pelo sistema.', $type));
                break;
        }
    }
}
