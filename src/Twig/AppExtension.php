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
            new TwigFilter('strToArray', array($this, 'strToArray')),
            new TwigFilter('die', array($this, 'dieFunc'))
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

    /**
     * Recebe um array parecido com um json e o converte em um array
     *
     * @param string $toArrayConverter
     * @return array
     */
    public function strToArray(string $toArrayConverter): array
    {
        $toArrayConverter = str_replace('{"', '', str_replace('"}', '', $toArrayConverter));
        $array = [];
        foreach (explode('","', $toArrayConverter) as $key => $value) {
            $answerQuestion = explode('":"', $value);
            $array[$answerQuestion[0]] = $answerQuestion[1];
        }
        return $array;
    }

    public function dieFunc(): void
    {
        die;
    }
}
