<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\Departament;
use App\Entity\Feedstock;
use App\Entity\FeedstockInventory;
use App\Entity\Unit;

final class FeedstockModel extends Model
{
    /**
     * Função que persiste e atualiza produtos do inventário
     *
     * @param array $data Array associativo com dados a serem incluidos ou atualizados.
     * @param string $type [INSERT] insere no banco de dados. [UPDATE] atualiza o produto no banco de dados.
     * @param int $id Caso a TYPE [$type] for UPDATE, ID do produto.
     * @throws \Exception
     */
    public function persist(array $data, string $type = 'insert', ?int $id = null)
    {
        $type = strtolower($type);

        if ($type !== 'insert' && $type !== 'update') {
            throw new \Exception('Tipos suportados INSERT, UPDATE tipo <b>' . $type . '</b> não suportado');
        }

        if ($data['name'] === null ||
            $data['mainVendor'] === null ||
            $data['unit'] === null ||
            $data['departament'] === null ||
            $data['maxStock'] === null ||
            $data['minStock'] === null ||
            $data['periocid'] === null) {
            throw new \Exception('Parametro' . $data['name'] . 'não existe');
        }

        try {
            $feedstock = new Feedstock();
            $inventory = new FeedstockInventory();
            if ($type === 'update') {
                /** @var Feedstock $feedstock */
                $feedstock = $this->entityManager->getRepository(Feedstock::class)->find($id);
                /** @var FeedstockInventory $inventory */
                $inventory = $this->entityManager->getRepository(FeedstockInventory::class)->findOneBy([
                    'feedstock_id' => $id,
                ]);
            }
            $feedstock->setNome($data['name']);
            $feedstock->setDescription($data['description']);
            $feedstock->setPeriodicity((int) $data['periocid']);
            /** @var Unit $unit */
            $unit = $this->entityManager->getRepository(Unit::class)->find($data['unit']);
            $feedstock->setUnit($unit);
            $vendors = $data['otherVendors'] ?? '';
            isset($data['otherVendors']) ?
                array_unshift($vendors, $data['mainVendor']) :
                $vendors = (array) $data['mainVendor'];

            $feedstock->setVendors($vendors);
            /** @var Departament $departament */
            $departament = $this->entityManager->getRepository(Departament::class)->find($data['departament']);
            $feedstock->setDepartament($departament);
            if ($type === 'insert') {
                $inventory->setFeedstockId($feedstock);
            }
            $inventory->setMaxStock($data['maxStock']);
            $inventory->setMinStock($data['minStock']);
            $feedstock->setLastUpdate();
            $this->entityManager->persist($feedstock);
            $this->entityManager->persist($inventory);
        } catch (\Exception $e) {
            throw new \Exception(
                sprintf(
                    'Erro - %s. Arquivo - %s. Linha - %s.',
                    $e->getMessage(),
                    $e->getFile(),
                    $e->getLine()
                )
            );
        }

        $this->entityManager->flush();
    }

    /**
     * @param array $data
     * @param int $productId
     * @throws \Exception
     */
    public function update(array $data, int $productId)
    {
        $this->persist($data, 'update', $productId);
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function feedIn(array $data): void
    {
        //$day = $data['date'];
        unset($data['date']);
        $values = array_values($data);

        try {
            foreach ($values as $inv) {
                $feedstock = $this->entityManager->getRepository(FeedStockInventory::class)->findOneBy([
                    'feedstock_id' => $inv['name'],
                ]);

                $actualStock = $feedstock->getStock();
                $newStock = $actualStock + $inv['amount'];

                $feedstock->setStock((string) $newStock);

                $this->entityManager->merge($feedstock);
            }
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function feedOut(array $data): array
    {
        //$day = $data['date'];
        unset($data['date']);

        $values = array_values($data);

        try {
            foreach ($values as $inv) {
                $feedstock = $this->entityManager->getRepository(FeedstockInventory::class)->findOneBy([
                    'feedstock_id' => $inv['name'],
                ]);

                $actualStock = $feedstock->getStock();

                if ($actualStock < $inv['amount']) {
                    return [
                        'http_code' => 203,
                        'message' => 'Retirada maior que estoque',
                    ];
                }

                $newStock = $actualStock - $inv['amount'];

                $feedstock->setStock((string) $newStock);
                $this->entityManager->merge($feedstock);
            }
            $this->entityManager->flush();

            return ['http_code' => 200, 'message' => 'Sucesso!'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
