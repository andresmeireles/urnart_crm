<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

class MyDateTime
{
    private $date;

    public function __construct(string $date = 'now')
    {
        $this->date = new \DateTime($date, new \DateTimeZone('America/Belem'));
    }

    public function format(string $format = 'd/m/Y h:m:s'): string
    {
        return $this->date->format($format);
    }
}