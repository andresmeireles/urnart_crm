<?php declare(strict_types = 1);

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('rot13', array($this, 'rot13Filter')),
            new TwigFilter('makeHash', array($this, 'makeHash')),
        );
    }

    public function rot13Filter(string $string): string
    {
        $rot13String = str_rot13($string);

        return $rot13String;
    }

    public function makeHash(string $hasheableValue, string $hashType = 'ripemd160'): string
    {
        $hasedString = hash($hashType, $hasheableValue);
        return $hasedString;
    }
}
