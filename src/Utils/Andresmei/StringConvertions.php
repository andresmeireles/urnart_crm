<?php

declare(strict_types=1);

namespace App\Utils\Andresmei;

use Symfony\Component\Cache\Exception\LogicException;

/**
 * Class StringConvertions.
 */
final class StringConvertions
{
    public function snakeToCamelCase(string $snakeCaseString): string
    {
        $newStr = str_replace('_', ' ', $snakeCaseString);
        $newStr = str_replace('-', ' ', $snakeCaseString);
        $newStr = ucwords($newStr);
        $newStr = str_replace(' ', '', $newStr);
        $newStr[0] = strtolower($newStr[0]);

        return (string) $newStr;
    }

    public function moneyToFloat(string $money): float
    {
        $commaToFloat = str_replace(',', '.', $money);

        return (float) $commaToFloat;
    }

    public function emptyToNull(string $emptyString): ?string
    {
        if ('' === $emptyString) {
            return null;
        }

        return $emptyString;
    }

    /**
     * @throws \Exception
     */
    public function strToDateString(?string $dateString): ?string
    {
        dump($dateString);
        die;
        if (null === $dateString || '' === $dateString) {
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
     * @throws \Exception
     */
    public function convertStringToDate(
        string $string,
        string $delimiter = '/',
        string $type = 'POR',
        string $divisor = '-'
    ): string {
        switch (strtoupper($type)) {
            case 'POR':
                $date = explode($delimiter, $string);
                $day = (int) $date[0];
                $month = (int) $date[1];
                $year = (int) $date[2];
                if (!checkdate($month, $day, $year)) {
                    throw new LogicException(sprintf('Data informada DIA = %s, MES = %s, ANO = %s não é valida.', $day, $month, $year));
                }

                return "{$month}{$divisor}{$day}{$divisor}{$year}";
                break;
            default:
                throw new \Exception(sprintf('Tipo de formação de data %s não suportdado pelo sistema.', $type));
                break;
        }
    }

    /**
     * @param $value
     *
     * @return array|bool|float|int|string
     *
     * @throws \Exception
     */
    public function convertValue(string $type, $value)
    {
        switch ($type) {
            case 'string':
                return (string) $value;
                break;
            case 'int':
                return (int) $value;
                break;
            case 'bool':
                return (bool) $value;
                break;
            case 'array':
                return (array) $value;
                break;
            case 'float':
                return (float) $value;
                break;
            case 'none':
                return $value;
                break;
            default:
                throw new \Exception(sprintf('Tipo %s não reconhecido', $type));
                break;
        }
    }
}
