<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\Model as ModelName;
use App\Utils\Exceptions\CustomException;
use App\Utils\Andresmei\FlashResponse;

class ModelsModel extends Model
{
    public function insertModel(
        string $name,
        string $height,
        float $suggestedPrice,
        ?string $specificity,
        ?array $colors
    ): FlashResponse {
        $entityManager = $this->em;
        try {
            $modelObject = new ModelName();
            $criteria = [
                'name' => $name,
                'height' => $height,
                'specificity' => $specificity
            ];
            if ($this->checkIfExists(get_class($modelObject), $criteria)) {
                throw new CustomException(
                    sprintf('Não é possivel inserir o item %s %s %s, item já existe com nome cadastrado.',
                    $name,
                    $height,
                    $specificity)
                );
            }
            $modelObject->setName($name);
            $modelObject->setHeight($height);
            $modelObject->setSpecificity($specificity);
            $modelObject->setColors($colors);
            $modelObject->setSuggestedPrice($suggestedPrice);
            $entityManager->persist($modelObject);
            $entityManager->flush();
        } catch (CustomException $err) {
            throw new CustomException($err->getMessage());
        }

        return new FlashResponse(200, 'success', 'Produto inserido com sucesso.');
    }
}
