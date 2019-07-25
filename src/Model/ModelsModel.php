<?php declare(strict_types = 1);

namespace App\Model;

use App\Entity\ModelName;
use App\Utils\Andresmei\FlashResponse;
use App\Utils\Exceptions\CustomException;
use Doctrine\Common\Persistence\ObjectManager;

final class ModelsModel extends Model
{
    /**
     * @param string $name
     * @param string $height
     * @param float $suggestedPrice
     * @param string|null $specificity
     * @param array|null $colors
     * @return FlashResponse
     * @throws CustomException
     */
    public function insertModel(
        string $name,
        string $height,
        float $suggestedPrice,
        ?string $specificity,
        ?array $colors
    ): FlashResponse {
        $entityManager = $this->entityManager;
        try {
            $modelObject = new ModelName();
            $criteria = [
                'name' => $name,
                'height' => $height,
                'specificity' => $specificity,
            ];
            if ($this->checkIfExists(get_class($modelObject), $criteria)) {
                throw new CustomException(
                    sprintf(
                        'Não é possivel inserir o item %s %s %s, item já existe com nome cadastrado.',
                        $name,
                        $height,
                        $specificity
                    )
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

    /**
     * @return iterable
     */
    public function getModelPrices(): iterable
    {
        $allRegisters = $this->entityManager->getRepository(ModelName::class)->findAll();
        $formatNames = [];
        foreach ($allRegisters as $register) {
            $name = sprintf(
                '%s %s %s',
                $register->getName(),
                $register->getHeight(),
                $register->getSpecificity() ?? ''
            );
            $amount['price'] = $register->getSuggestedPrice();
            $formatNames[trim($name)] = $amount;
        }

        return $formatNames;
    }
}
