<?php declare(strict_types = 1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class SlugExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('slugfy', [$this, 'slugfy'], ['is_safe' => ['html']]),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('slugfy', [$this, 'slugfy']),
        ];
    }

    /**
     * @param $value
     * @return string
     */
    public function slugfy(string $value): string
    {
        $value = trim($value);
        return strtolower(str_replace(' ', '-', $value));
    }
}
