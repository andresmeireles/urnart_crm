<?php
declare(strict_types=1);

namespace App\Model;

use App\Entity\Product;
use App\Entity\ProductInventory;
use App\Utils\Andresmei\FlashResponse;

class ProductModel extends Model
{
    /**
     * Execuca ação de inserir dados no banco de dados, atravez de uma chamada a função runAction
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function insert(array $data): array
    {
        $result = $this->runAction($data, null, 'insert');
        return $result;
    }

    /**
     * Executa a ação de atualizar dados no banco de dados dado id, atravez de uma chamada a função runAction
     *
     * @param array $data
     * @param integer $productId
     * @return array
     * @throws \Exception
     */
    public function update(array $data, int $productId): array
    {
        $result = $this->runAction($data, $productId, 'update');
        return $result;
    }

    /**
     * @param int $productId
     * @return array
     */
    public function remove(int $productId): array
    {
        $product = $this->em->getRepository(Product::class)->find($productId);

        $this->em->remove($product);
        $this->em->flush();

        return array(
            'http_code' => 200,
            'message' => "Produto {$product->getName()} removido com sucesso!"
        );
    }

    /**
     * Executa a ação no modelo
     *
     * @param array $data
     * @param integer $id
     * @param string $unclearType
     * @return array
     * @throws \Exception
     */
    public function runAction(array $data, int $id = null, string $unclearType): array
    {
        if (
            \is_null($data['name']) ||
            \is_null($data['maxStock']) ||
            \is_null($data['minStock']) ||
            \is_null($data['price'])
        ) {
            return array(
                'http_code' => 203,
                'message' => 'Deu erro meu amiginho'
            );
        }

        $type = strtolower($unclearType);
        $allowParameters = array(
            'insert',
            'update'
        );

        if (!in_array($type, $allowParameters)) {
            throw new \Exception("Operaçao {$type} não suportada, operações suportadas: INSERT e UPDATE");
        }

        $this->em->getConnection()->beginTransaction();
        try {
            $product = new Product;
            $name = strtolower($data['name']);
            if ($type === 'insert') {
                $result = $this->em->getRepository(Product::class)->findOneBy(array('name' => $name));
                if (!is_null($result)) {
                    return FlashResponse::response(400, 'warning', 'Produto com nome igual já cadastrado');
                }
            }
            $productInventory = new ProductInventory();
            if ($type == 'update') {
                $product = $this->em->getRepository(Product::class)->find($id);
                $productInventory = $product->getProductInventory();
            }

            $product->setName($name);
            $description = htmlentities($data['description'], ENT_QUOTES, 'UTF-8');
            
            $price = (float) $data['price'];
            $product->setPrice($price);
            $color = $data['colors'] ?? array();
            $product->setColor($color);
            $product->setSeries($data['model']);
            if ($type == 'insert') {
                $productInventory->setProduct($product);
            }
            $minStock = (float) $data['maxStock'];
            $productInventory->setMinStock($minStock);
            $maxStock = (int) $data['maxStock'];
            $productInventory->setMaxStock($maxStock);
            if ($type == 'insert') {
                $this->em->persist($product);
                $this->em->persist($productInventory);
            }
            if ($type == 'update') {
                $this->em->merge($product);
                $this->em->merge($productInventory);
            }
            $this->em->flush();
            $this->em->getConnection()->commit();
            return array(
                'http_code' => 200,
                'type' => 'success',
                'message' => 'Sucesso'
            );
        } catch (\Exception $e) {
            throw new \Exception("Erro: {$e->getMessage()}. Arquivo: {$e->getFile()}. Linha: {$e->getLine()}");
            $this->em->getConnection()->rollback();
        }
    }

    /**
     * @param object $data
     * 
     * @return array
     */
    public function productIn(object $data): array
    {
        $date = $data->date;
        unset($data->date);
        $this->em->getConnection()->beginTransaction();
        try {
            foreach ($data as $info) {
                $productInventory = $this->em->getRepository(ProductInventory::class)->findOneBy(array(
                    'product' => $info->product
                ));
                $oldStock = $productInventory->getStock();
                $stock = (float) $info->amount;
                $newStock = $stock + $oldStock;
                $productInventory->setStock($newStock);
                $this->em->merge($productInventory);
            }
            $this->em->flush();
            $this->em->getConnection()->commit();
            return array(
                'http_code' => 200,
                'message' => 'Sucesso!'
            );
        } catch (\Exception $e) {
            $this->em->getConnection()->rollback();
            return array(
                'http_code' => 203,
                'message' => $e->getMessage()
            );
        }
    }
}
