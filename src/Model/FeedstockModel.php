<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\Departament;
use App\Entity\Feedstock;
use App\Entity\FeedstockInventory;
use App\Entity\Unit;

/**
 * TESTS OK
 */
class FeedstockModel extends Model
{
    /**
     * Função que persiste e atualiza produtos do inventário
     *
     * @param array $data Array associativo com dados a serem incluidos ou atualizados.
     * @param string $type [INSERT] insere no banco de dados. [UPDATE] atualiza o produto no banco de dados.
     * @param int $id Caso a TYPE [$type] for UPDATE, ID do produto.
     * @return void
     * @throws \Exception
     */
    public function persist(array $data, string $type = 'insert', ?int $id = null)
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

        try {
            $feedstock = new Feedstock();
            $inventory = new FeedstockInventory();
            if ($type === 'update') {
                /** @var Feedstock $feedstock */
                $feedstock = $this->em->getRepository(Feedstock::class)->find($id);
                /** @var FeedstockInventory $inventory */
                $inventory = $this->em->getRepository(FeedstockInventory::class)->findOneBy([
                    'feedstock_id' => $id
                ]);
            }
            $feedstock->setNome($data['name']);
            $feedstock->setDescription($data['description']);
            $feedstock->setPeriodicity((int) $data['periocid']);
            $unit = $this->em->getRepository(Unit::class)->find($data['unit']);
            $feedstock->setUnit($unit);
            $vendors = $data['otherVendors'] ?? '';
            isset($data['otherVendors']) ?
                array_unshift($vendors, $data['mainVendor']) :
                $vendors = (array) $data['mainVendor'];
    
            $feedstock->setVendors($vendors);
            $departament = $this->em->getRepository(Departament::class)->find($data['departament']);
            $feedstock->setDepartament($departament);
            if ($type === 'insert') {
                $inventory->setFeedstockId($feedstock);
            }
            $inventory->setMaxStock($data['maxStock']);
            $inventory->setMinStock($data['minStock']);
            $feedstock->setLastUpdate();
            $this->em->persist($feedstock);
            $this->em->persist($inventory);
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

        $this->em->flush();
    }

    /**
     * Um wrapper para função PERSIST com atributos predefinidos
     *
     * @param array $data
     * @param int $productId
     *
     * @return void
     * @throws \Exception
     */
    public function update(array $data, int $productId)
    {
        $this->persist($data, 'update', $productId);
    }

    public function feedIn(array $data): void
    {
        //$day = $data['date'];
        unset($data['date']);
        $values = array_values($data);

        try {
            foreach ($values as $inv) {
                $feedstock = $this->em->getRepository(FeedStockInventory::class)->findOneBy([
                    'feedstock_id' => $inv['name'],
                ]);

                $actualStock = $feedstock->getStock();
                $newStock = $actualStock + $inv['amount'];

                $feedstock->setStock((string) $newStock);

                $this->em->merge($feedstock);
            }
            $this->em->flush();            
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    /**
     * Retira produto do estoque.
     *
     * @param array $data
     * @return array => retorna 203 quando há erro
     */
    public function feedOut(array $data): array
    {
        //$day = $data['date'];
        unset($data['date']);

        $values = array_values($data);
        
        try {
            foreach ($values as $inv) {
                $feedstock = $this->em->getRepository(FeedStockInventory::class)->findOneBy([
                    'feedstock_id' => $inv['name'],
                ]);

                $actualStock = $feedstock->getStock();
                
                if ($actualStock < $inv['amount']) {
                    return [
                        'http_code' => 203,
                        'message' => 'Retirada maior que estoque'
                    ];
                }

                $newStock = $actualStock - $inv['amount'];
                 
                $feedstock->setStock((string) $newStock);
                $this->em->merge($feedstock);
            }
            $this->em->flush();

            return ['http_code' => 200, 'message' => 'Sucesso!'];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
