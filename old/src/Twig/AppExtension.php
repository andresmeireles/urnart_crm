<?php declare(strict_types = 1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use WGenial\NumeroPorExtenso\NumeroPorExtenso;

final class AppExtension extends AbstractExtension
{
    /**
     * @return TwigFilter[]
     */
    public function getFilters(): array
    {
        return [
            new TwigFilter('rot13', [$this, 'rot13Filter']),
            new TwigFilter('makeHash', [$this, 'makeHash']),
            new TwigFilter('extense', [$this, 'extense']),
            new TwigFilter('strToArray', [$this, 'strToArray']),
            new TwigFilter('die', [$this, 'dieFunc']),
        ];
    }

    /**
     * @param string $string
     * @return string
     */
    public function rot13Filter(string $string): string
    {
        return str_rot13($string);
    }

    /**
     * @param string $hashableValue
     * @return string
     */
    public function makeHash(string $hashableValue): string
    {
        return hash('ripemd160', $hashableValue);
    }

    /**
     * @param float $number
     * @return string
     */
    public function extense(float $number): string
    {
        $extense = new NumeroPorExtenso();
        return $extense->converter($number);
    }

    /**
     * @param string $toArrayConverter
     * @return array
     */
    public function strToArray(string $toArrayConverter): array
    {
        $toArrayConverter = str_replace('{"', '', str_replace('"}', '', $toArrayConverter));
        $array = [];
        foreach (explode('","', $toArrayConverter) as $value) {
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
