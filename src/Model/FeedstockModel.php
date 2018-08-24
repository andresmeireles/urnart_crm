<?php declare(strict_types=1);

namespace App\Model;

use App\Entity\Unit;
use App\Entity\Feedstock;
use App\Entity\FeedstockInventory;
use App\Entity\Departament;

class FeedstockModel extends Model
{
    public function persist(array $data, $type = 'insert', $id = null): void
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
            if ($type == 'update') {
                $feedstock = $this->em->getRepository(Feedstock::class)->find($id);
                $inventory = $this->em->getRepository(FeedstockInventory::class)->findOneBy(array(
                    'feedstock_id' => $id
                ));
            } else {
                $feedstock = new Feedstock();
                $inventory = new FeedstockInventory();
            }

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

            $inventory->setFeedstockId($feedstock);
            $inventory->setMaxStock($data['maxStock']);
            $inventory->setMinStock($data['minStock']);

            $feedstock->setLastUpdate();

            $this->em->persist($feedstock);
            $this->em->persist($inventory);

            $this->em->flush();
            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            throw new \Exception("Erro - " . $e->getMessage() . '. Arquivo - ' . $e->getFile() . '. Linha - ' . $e->getLine());
        }
    }

    public function update(array $data, int $id): void
    {
        $this->persist($data, 'update', $id);
    }

    public function feedIn(array $data): void
    {
        $day = $data['date'];
        unset($data['date']);

        $values = array_values($data);

        dump($values);
        die();
    }

    public function remove()
    {

    }
}