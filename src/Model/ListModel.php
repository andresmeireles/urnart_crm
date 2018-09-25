<?php
declare(strict_types = 1);

namespace App\Model;

class ListModel extends Model
{
    public function select(string $type)
    {
        switch ($type) {
            case 'client':
                return $this->getParsedClientData();
                break;
            case 'date':
                return $this->getParsedDateData();
            default:
                return 'tipo de relatorio não existe';
                break;
        }
    }

    public function getParsedClientData()
    {
        
    }

    public function getParsedDateData()
    {

    }
}
