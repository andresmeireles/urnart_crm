<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

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
}