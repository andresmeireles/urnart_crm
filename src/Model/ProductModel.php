<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Product;
use App\Entity\ProductInventory;

class ProductModel extends Model
{
    public function insert(array $data): array
    {
        
    }

    public function update(array $data, int $id): array
    {

    }

    private function runAction(array $data, int $id, string $unclearType): void
    {
        $type = strtolower($unclearType);

        $allowParameters = array(
            'insert',
            'upate'
        );

        if (!in_array($type)) {
            throw new \Exception("Operaçao {$type} não suportada, operações suportadas: INSERT e UPDATE");
        }

        $this->em->getConnection()->beginTransaction();

        try {

            $product = new Product();
            $productInventory = new ProductInventory();

            if ($type == 'update') {
                $product = $this->em->getRespository(Product::class)->find($id);
                $productInventory = $product->getProductInvetory();
            }

            $this->em->getConnection()->commit();
        } catch (\Exception $e) {
            throw new \Exception("Erro: {$e->getMessage()}. Arquivo: {$e->getFile()}. Linha: {$e->getLine()}");
            $this->em->getConnection()->rollback();
        }
    }
}