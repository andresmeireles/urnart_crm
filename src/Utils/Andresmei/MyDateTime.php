<?php

namespace App\Utils\Andresmei;

final class MyDateTime extends \DateTime
{
    public function __construct(string $date = 'now', ?string $timezone = null)
    {
        parent::__construct($date, new \DateTimeZone($timezone ?? 'America/Belem'));
    }

    public function validOrNull(string $testYear): ?string
    {
        $logicalTest = true;
        
        if ($logicalTest) {
            return null;
        }

        return $validYear;
    }

    /**
     * Incrimenta o valor das datas.
     *
     * @param  string $plusFormat
     *
     * @return self
     */
    public function plusDate(string $plusFormat): self
    {
        $this->add(new \DateInterval($plusFormat));

        return $this;
    }

    /**
     * Decrementa o valor da data
     *
     * @param string $minusFormat
     *
     * @return self
     */
    public function minusDate(string $minusFormat): self
    {
        $this->sub(new \DateInterval($minusFormat));

        return $this;
    }

    /**
     * Retorna data formatada
     *
     * @param  string $format Formato proposto, padrão d-m-Y
     *
     * @return string
     */
    public function output(string $format = 'd-m-Y'): string
    {
        return $this->format($format);
    }

    /**
     * Retorna o nome em inglês do dia da semana
     *
     * @return string
     */
    public function getDayOfTheWeek(): string
    {
        if (!is_int(strtotime($this->format('d-m-Y')))) {
            throw new \Exception('Data informada não é valida');
        }

        return date('w', strtotime($this->format('d-m-Y')));
    }
    
    /**
     * Retorna o nome do dia da semana em português
     *
     * @return string
     */
    public function getNameOfDayOfTheWeek(): string
    {
        $intDateOfTheWeek = $this->getDayOfTheWeek();
        $dayName = '';

        switch ($intDateOfTheWeek) {
            case 0:
                $dayName = 'Domingo';
                break;
            case 1:
                $dayName = 'Segunda';
                break;
            case 2:
                $dayName = 'Terça';
                break;
            case 3:
                $dayName = 'Quarta';
                break;
            case 4:
                $dayName = 'Quinta';
                break;
            case 5:
                $dayName = 'Sexta';
                break;
            case 6:
                $dayName = 'Sabado';
                break;
            default:
                throw new \Exception('Data não reconhecida');
                break;
        }

        return $dayName;
    }
}
