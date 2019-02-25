<?php

namespace App\Utils\Andresmei;

final class MyDateTime extends \DateTime
{ 
    public function __construct(string $date = 'now')
    {
        parent::__construct($date);  
    }

    public function getDayOfTheWeek()
    {
        if (!is_int(strtotime($this->format('d-m-Y')))) {
            throw new \Exception('Data informada não é valida');
        }

        return date('w', strtotime($this->format('d-m-Y') ));
    }
    
    public function getNameOfDayOfTheWeek()
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