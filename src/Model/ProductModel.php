<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\Product;
use App\Entity\ProductInventory;
use App\Utils\Andresmei\FlashResponse;

final class ProductModel extends Model
{
    /**
     * @param array $data
     * @return FlashResponse
     * @throws \Exception
     */
    public function insert(array $data): FlashResponse
    {
        return $this->runAction($data, null, 'insert');
    }

    /**
     * @param array $data
     * @param int $productId
     * @return FlashResponse
     * @throws \Exception
     */
    public function update(array $data, int $productId): FlashResponse
    {
        return $this->runAction($data, $productId, 'update');
    }

    /**
     * @param int $productId
     * @return array
     */
    public function remove(int $productId): array
    {
        $product = $this->entityManager->getRepository(Product::class)->find($productId);

        $this->entityManager->remove($product);
        $this->entityManager->flush();

        return [
            'http_code' => 200,
            'message' => sprintf(
                'Produto %s removido com sucesso!',
                $product->getName()
            ),
        ];
    }

    /**
     * @param array $data
     * @param int|null $elementId
     * @param string $unclearType
     * @return FlashResponse
     * @throws \Exception
     */
    public function runAction(
        array $data,
        ?int $elementId,
        string $unclearType
    ): FlashResponse {
        if ($data['name'] === null ||
            $data['maxStock'] === null ||
            $data['minStock'] === null ||
            $data['price'] === null
        ) {
            return new FlashResponse(203, 'success', 'Deu erro meu amiginho');
        }
        $type = strtolower($unclearType);
        $allowParameters = [
            'insert',
            'update',
        ];
        if (! in_array($type, $allowParameters, true)) {
            throw new \Exception(
                sprintf(
                    'Operaçao %s não suportada, operações suportadas: INSERT e UPDATE',
                    $type
                )
            );
        }
        try {
            $product = new Product();
            $name = strtolower($data['name']);
            if ($type === 'insert') {
                $result = $this->entityManager->getRepository(Product::class)->findOneBy(['name' => $name]);
                if ($result !== null) {
                    return new FlashResponse(400, 'warning', 'Produto com nome igual já cadastrado');
                }
            }
            $productInventory = new ProductInventory();
            if ($type === 'update') {
                $product = $this->entityManager->getRepository(Product::class)->find($elementId);
                $productInventory = $product->getProductInventory();
            }
            $product->setName($name);
            $price = (float) $data['price'];
            $product->setPrice($price);
            $color = $data['colors'] ?? [];
            $product->setColor($color);
            $product->setSeries($data['model']);
            if ($type === 'insert') {
                $productInventory->setProduct($product);
            }
            $minStock = (float) $data['maxStock'];
            $productInventory->setMinStock($minStock);
            $maxStock = (int) $data['maxStock'];
            $productInventory->setMaxStock($maxStock);
            if ($type === 'insert') {
                $this->entityManager->persist($product);
                $this->entityManager->persist($productInventory);
            }
            if ($type === 'update') {
                $this->entityManager->merge($product);
                $this->entityManager->merge($productInventory);
            }
            $this->entityManager->flush();

            return new FlashResponse(200, 'success', 'Sucesso');
        } catch (\Exception $e) {
            throw new \Exception("Erro: {$e->getMessage()}. Arquivo: {$e->getFile()}. Linha: {$e->getLine()}");
        }
    }

    /**
     * @param array $data
     * @return array
     */
    public function productIn(array $data): array
    {
        $this->entityManager->getConnection()->beginTransaction();
        try {
            foreach ($data as $info) {
                $productInventory = $this->entityManager->getRepository(ProductInventory::class)->findOneBy([
                    'product' => $info->product,
                ]);
                $oldStock = $productInventory->getStock();
                $stock = (float) $info->amount;
                $newStock = $stock + $oldStock;
                $productInventory->setStock($newStock);
                $this->entityManager->merge($productInventory);
            }
            $this->entityManager->flush();
            $this->entityManager->getConnection()->commit();
            return [
                'http_code' => 200,
                'message' => 'Sucesso!',
            ];
        } catch (\Exception $e) {
            $this->entityManager->getConnection()->rollback();
            return [
                'http_code' => 203,
                'message' => $e->getMessage(),
            ];
        }
    }
}
