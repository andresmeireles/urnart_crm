<?php declare(strict_types=1);

namespace App\Model;

use App\Entity\Unit;
use App\Model\Model;
use App\Entity\Feedstock;
use App\Entity\FeedstockInvetory;
use App\Entity\Departament;
use App\Entity\FeedstockInventory;

class FeedstockModel extends Model
{
    public function persist(array $data, $type = 'insert'): void
    {
        $type = strtolower($type);

        if ($type != 'insert' && $type != 'update') {
            throw new \Exception('Tipos suportados INSERT, UPDATE tipo <b>' . $type . '</b> não suportado');
        }

        if (is_null($data['name']) ||
            is_null($data['mainVendor']) ||
            is_null($data['unit']) ||
            is_null($data['departament']) ||
            is_null($data['maxStock']) ||
            is_null($data['minStock']) ||
            is_null($data['periocid'])) {
            throw new \Exception('Parametro' . $data['name'] . 'não existe');
        }

        $this->em->getConnection()->beginTransaction();
        try {
            $feedstock = new Feedstock();
            $feedstock->setNome($data['name']);
            $feedstock->setDescription($data['description']);
            $feedstock->setPeriodicity((int)$data['periocid']);

            $unit = $this->em->getRepository(Unit::class)->find($data['unit']);
            $feedstock->setUnit($unit);

            $vendors = $data['otherVendors'] ?? '';

            isset($data['otherVendors']) ? array_unshift($vendors, $data['mainVendor']) : $vendors = (array)$data['mainVendor'];
            $feedstock->setVendors($vendors);

            $departament = $this->em->getRepository(Departament::class)->find($data['departament']);
            $feedstock->setDepartament($departament);

            $inventory = new FeedstockInventory();
            $inventory->setFeedstockId($feedstock);
            $inventory->setStock('0');
            $inventory->setMaxStock($data['maxStock']);
            $inventory->setMinStock($data['minStock']);

            $this->em->persist($feedstock);
            $this->em->persist($inventory);

            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            throw new \Exception("Erro - " . $e->getMessage() . '. Arquivo - ' . $e->getFile() . '. Linha - ' . $e->getLine());
        }
    }

    public function update(array $data): void
    {
        $this->persist($data, 'update');
    }

    public function remove()
    {

    }
}