<?php declare(strict_types = 1);

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;
use WGenial\NumeroPorExtenso\NumeroPorExtenso;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('rot13', array($this, 'rot13Filter')),
            new TwigFilter('makeHash', array($this, 'makeHash')),
            new TwigFilter('extense', array($this, 'extense')),
        );
    }

    public function rot13Filter(string $string): string
    {
        $rot13String = str_rot13($string);

        return $rot13String;
    }

    public function makeHash(string $hashableValue): string
    {
        $hasedString = hash('ripemd160', $hashableValue);
        return $hasedString;
    }

    public function extense(float $number): string
    {
        $extense = new NumeroPorExtenso();
        $extense = $extense->converter($number);
        return $extense;
    }
}
