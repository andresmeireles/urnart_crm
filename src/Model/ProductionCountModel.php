<?php declare(strict_types=1);

namespace App\Model;

use App\Entity\ProductionCount;
use App\Entity\ModelName;
use App\Utils\Andresmei\FlashResponse;

class ProductionCountModel extends Model
{
    public function createProductionCount(array $productionInformation)
    {
        $entityManager = $this->em;
        $modelRepository = $entityManager->getRepository(ModelName::class);
        try {
            foreach ($productionInformation as $productionItem) {
                $production = new ProductionCount();
                $idAndColor = explode('-', $productionItem['model']);
                /** @var ModelName $modelItem */
                $modelItem = $modelRepository->find($idAndColor[0]);
                $production->setModel($modelItem->getName());
                $production->setHeight($modelItem->getHeight());
                $production->setObs(
                    $modelItem->getSpecificity() === ''?
                    null:
                    $modelItem->getSpecificity()
                );
                $production->setAmount((int) $productionItem['amount']);
                $production->setDate($productionItem['date']);
                $production->setColor($idAndColor[1] ?? null);
                $production->setActive(true);
                $entityManager->persist($production);
            }
            $entityManager->flush();
        } catch (\Exception $error) {
            throw new \Exception($error->getMessage());
        }

        return new FlashResponse(200, 'success', 'Produto inserido com sucesso');
    }
}