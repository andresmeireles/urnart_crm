<?php
namespace App\Model;

use App\Entity\Feedstock;
use App\Entity\FeedstockInvetory;
use App\Model\Model;

class FeedstockModel extends Model
{
    public function persist(array $data)
    {
        dump($this);
    }

    public function update()
    {

    }

    public function remove()
    {

    }
}