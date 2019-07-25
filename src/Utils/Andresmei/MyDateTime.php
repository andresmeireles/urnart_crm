<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;

final class MyDateTime extends \DateTime
{
    private const THIRTYONEDATMONTH = [
        '01' => 'Janeiro',
        '03' => 'Março',
        '05' => 'Maio',
        '07' => 'Julho',
        '08' => 'Agosto',
        '10' => 'Outubro',
        '12' => 'Dezembro',
    ];

    private const THIRTYDAYMONTH = [
        '04' => 'Abril',
        '06' => 'Junho',
        '09' => 'Setembro',
        '11' => 'Novembro',
    ];

    public function __construct(string $date = 'now', ?string $timezone = null)
    {
        parent::__construct($date, new \DateTimeZone($timezone ?? 'America/Belem'));
    }

    /**
     * @param string $plusFormat
     * @return MyDateTime
     * @throws \Exception
     */
    public function plusDate(string $plusFormat): self
    {
        $this->add(new \DateInterval($plusFormat));

        return $this;
    }

    /**
     * @param string $minusFormat
     * @return MyDateTime
     * @throws \Exception
     */
    public function minusDate(string $minusFormat): self
    {
        $this->sub(new \DateInterval($minusFormat));

        return $this;
    }

    /**
     * @param string $format
     * @return string
     */
    public function output(string $format = 'd-m-Y'): string
    {
        return $this->format($format);
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function getDayOfTheWeek(): string
    {
        if (! is_int(strtotime($this->format('d-m-Y')))) {
            throw new \Exception('Data informada não é valida');
        }

        return date('w', strtotime($this->format('d-m-Y')));
    }

    /**
     * @return string
     * @throws \Exception
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

    /**
     * @param string $month
     * @return string
     */
    public static function getLastDayOfMonth($month): string
    {
        if (array_key_exists($month, self::THIRTYONEDATMONTH) ||
            in_array($month, self::THIRTYONEDATMONTH, false))
        {
            return '31';
        }

        if (array_key_exists($month, self::THIRTYONEDATMONTH) ||
            in_array($month, self::THIRTYONEDATMONTH, false))
        {
            return '30';
        }

        return '28';
    }
}
