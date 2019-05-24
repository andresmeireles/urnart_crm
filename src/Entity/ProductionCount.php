<?php declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Utils\Andresmei\StringConvertions;
use App\Utils\Exceptions\CustomException;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ProductionCountRepository")
 */
class ProductionCount extends BaseEntity
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $model;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $height;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $obs;

    /**
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): self
    {
        $modelConvertions = [
            'ECOCVSV' => 'ECO CV SV',
            'ECOSVSV' => 'ECO SV SV',
            'ECOCV' => 'ECO CV',
            'ECOSV' => 'ECO SV'
        ];
        $cleanedModel = strtoupper($model);
        $cleanedModel = ltrim(trim($cleanedModel));
        if ($cleanedModel === 'S') {
            $cleanedModel = 'SL';
        }
        $cleanedModel = str_replace(' ', '', $cleanedModel);
        if (array_key_exists($cleanedModel, $modelConvertions)) {
            $cleanedModel = $modelConvertions[$cleanedModel];
        }
        $this->model = $cleanedModel;

        return $this;
    }

    public function getFullHeight(): string
    {
        $fullHeight = sprintf('%s %s', $this->height, $this->obs);

        return $fullHeight;
    }

    public function getHeight(): ?string
    {
        return $this->height;
    }

    public function setHeight(string $height): self
    {
        $smallHeights = [
            50 => '050',
            70 => '070',
            90 => '090',
            11 => '110',
            13 => '130',
            14 => '140',
            15 => '150',
            16 => '160',
            17 => '170',
            18 => '180',
            19 => '190',
            21 => '210',
            1  => 'T1',
            2  => 'T2',
            3  => 'T3',
            'T1' => 'T1',
            'T2' => 'T2',
            'T3' => 'T3'
        ];
        $cleanedHeight = ltrim(trim($height));
        $cleanedHeight = strtoupper($cleanedHeight);
        if (strlen($cleanedHeight) > 3) {
            throw new CustomException(sprintf(
                'Tamanho %s tem mais de três digitos, tamanhos não devem ter mais de 3 digitos.',
                $cleanedHeight
            ));
        }
        if (strlen($cleanedHeight) <= 2) {
            if (!array_key_exists((int) $cleanedHeight, $smallHeights)) {
                throw new CustomException(sprintf(
                    'Tamanho %s não é reconhecido, insira um tamanho valido.',
                    $cleanedHeight
                ));
            }
            $cleanedHeight = $smallHeights[(int) $cleanedHeight];
        }
        $this->height = $cleanedHeight;

        return $this;
    }

    public function getObs(): ?string
    {
        return $this->obs;
    }

    public function setObs(string $obs): self
    {
        $allowedHeights = ['SUPER GORDA', 'GORDA', 'BALEIA'];
        $uObs = strtoupper($obs);
        if (!in_array($uObs, $allowedHeights)) {
            throw new \Exception('Observação não existe.');
        }
        $this->obs = $uObs;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(int $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(string $productionDate): self
    {
        $date = (new StringConvertions())->convertStringToDate($productionDate, '-', 'POR', '/');
        $this->date = new \DateTime($date, new \DateTimeZone('America/Belem'));

        return $this;
    }
}
