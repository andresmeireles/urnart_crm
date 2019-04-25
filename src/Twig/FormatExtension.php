<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class FormatExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('format_brl', [$this, 'formatBrl']),
            new TwigFilter('month_coverter', array($this, 'monthConverter')),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'formatBrl']),
            new TwigFunction('function_name', array($this, 'monthConverter'))
        ];
    }

    public function formatBrl($number)
    {
        return number_format($number, 2, ',', '.');
    }

    public function monthConverter(string $monthConverter)
    {
        $month = [
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        ];

        $monthNumber = explode('-', $monthConverter);
        $monthNames = array_values($month);
        $date = array_search($monthNumber[0], array_keys($month));
        $monthName = $monthNames[$date];

        return sprintf("%s/%s", $monthName, $monthNumber[1]);
    }
}
