<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

class StringConvertions
{
    public function moneyToFloat(string $money): string
    {
        $removePoint = str_replace('.', '', $money);
        $commaToFloat = str_replace(',', '.', $removePoint);
        

        return $commaToFloat;
    }

    public function emptyToNull(string $emptyString): ?string
    {
        if ($emptyString === '') {
            return null;
        }
        return $emptyString;
    }
}