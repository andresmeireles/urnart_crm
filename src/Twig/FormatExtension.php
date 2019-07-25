<?php declare(strict_types = 1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

final class FormatExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('format_brl', [$this, 'formatBrl']),
            new TwigFilter('month_coverter', [$this, 'monthConverter']),
            new TwigFilter('month', [$this, 'numMonthConverter']),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('formatBrl', [$this, 'formatBrl']),
            new TwigFunction('month_converter', [$this, 'monthConverter']),
        ];
    }

    /**
     * @param $number
     * @return string
     */
    public function formatBrl($number)
    {
        $number = (float) $number;
        return number_format($number, 2, ',', '.');
    }

    /**
     * @param int $month
     * @return string
     */
    public function numMonthConverter(int $month): string
    {
        $months = [
            1 => 'Janeiro',
            2 => 'Fevereiro',
            3 => 'Março',
            4 => 'Abril',
            5 => 'Maio',
            6 => 'Junho',
            7 => 'Julho',
            8 => 'Agosto',
            9 => 'Setembro',
            10 => 'Outubro',
            11 => 'Novembro',
            12 => 'Dezembro',
        ];

        return $months[$month];
    }

    /**
     * @param string $monthConverter
     * @return string
     */
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
            '12' => 'Dezembro',
        ];

        $monthNumber = explode('-', $monthConverter);
        $monthNames = array_values($month);
        $date = array_search($monthNumber[0], array_keys($month), true);
        $monthName = $monthNames[$date];

        return sprintf('%s/%s', $monthName, $monthNumber[1]);
    }
}
