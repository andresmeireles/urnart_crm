<?php declare(strict_types = 1);

namespace App\Model;

use Andresmeireles\RespectAnnotation\RespectValidationAnnotation;
use App\Config\NonStaticConfig;
use App\Utils\Andresmei\StringConvertions;
use Doctrine\Common\Persistence\ObjectManager;
use JMS\Serializer\SerializerBuilder;
use Symfony\Component\Yaml\Yaml;

/**
 * Service Container for models
 */
abstract class Model
{
    /**
     * @var ObjectManager
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $config;

    /**
     * @var NonStaticConfig
     */
    protected $settings;

    /**
     * @var SerializerBuilder
     */
    protected $serializer;

    /**
     * @var RespectValidationAnnotation
     */
    private $validator;

    public function __construct(ObjectManager $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->config = Yaml::parse(__DIR__ . '/../Config/system-config.yaml');
        $this->settings = new NonStaticConfig();
        $this->serializer = SerializerBuilder::create()->build();
        $this->validator = new RespectValidationAnnotation();
    }

    /**
     * @return RespectValidationAnnotation
     */
    public function getValidator(): RespectValidationAnnotation
    {
        return $this->validator;
    }

    /**
     * @param string $repository
     * @param string $whereField
     * @param array $selectFields
     * @param string $beginDate Formato da data é DIA/MÊS/ANO
     * @param string $lastDate Formato da data é DIA/MÊS/ANO
     * @param string|null $orderBy
     * @param string|null $typeOfOrder
     * @return array
     * @throws \Exception
     */
    public function getGenericListByDateArrayFields(
        string $repository,
        string $whereField = 'createDate',
        array $selectFields = [],
        string $beginDate = '',
        string $lastDate = '',
        ?string $orderBy = null,
        ?string $typeOfOrder = null
    ): array {
        $selectFieldsString = '';
        foreach ($selectFields as $value) {
            $selectFieldsString .= sprintf('u.%s,', $value);
        }
        if ($selectFields === []) {
            $selectFieldsString = 'u';
        }
        $selectFieldsString = trim($selectFieldsString, ',');

        $repo = 'App\Entity\\' . $repository;
        $convertedBeginDate = (new StringConvertions())->strToDateString($beginDate);
        $convertedLastDate = (new StringConvertions())->strToDateString($lastDate);
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $result = null;
        $query = $queryBuilder->select($selectFieldsString)->from($repo, 'u');

        if ($convertedBeginDate === null && $convertedLastDate === null) {
            $result = $query->orderBy(sprintf('u.%s', $orderBy ?? 'id'), sprintf('%s', $typeOfOrder ?? 'ASC'));
        }
        if ($convertedBeginDate !== null && $convertedLastDate !== null) {
            $result = $query->where(sprintf('u.%s BETWEEN :begin AND :last', $whereField))
                ->setParameter('begin', sprintf('%s 00:00:00', $convertedBeginDate))
                ->setParameter('last', sprintf('%s 00:00:00', $convertedLastDate))
                ->orderBy(sprintf('u.%s', $orderBy ?? 'id'), sprintf('%s', $typeOfOrder ?? 'ASC'));
        }
        if ($convertedBeginDate === null && $convertedLastDate !== null) {
            $result = $query->where(sprintf('u.%s <= :date', $whereField))
                ->setParameter('date', sprintf('%s 23:00:00', $convertedLastDate))
                ->orderBy(sprintf('u.%s', $orderBy ?? 'id'), sprintf('%s', $typeOfOrder ?? 'ASC'));
        }
        if ($convertedBeginDate !== null && $convertedLastDate === null) {
            $result = $query->where(sprintf('u.%s >= :date', $whereField))
                ->setParameter('date', $convertedBeginDate)
                ->orderBy(sprintf('u.%s', $orderBy ?? 'id'), sprintf('%s', $typeOfOrder ?? 'ASC'));
        }

        return $result->getQuery()->getResult();
    }

    /**
     * @param string $dqlStringConsult
     * @return mixed
     */
    public function dqlQuery(string $dqlStringConsult)
    {
        $query = $this->entityManager->createQuery($dqlStringConsult);
        return $query->execute();
    }

    /**
     * @param string $repositoryCalss
     * @param array $criteria
     * @return bool
     */
    public function checkIfExists(string $repositoryCalss, array $criteria): bool
    {
        $consultResult = $this->entityManager->getRepository($repositoryCalss)->findBy($criteria);
        if ($consultResult === []) {
            return false;
        }

        return true;
    }

    /**
     * @param string $repository
     * @param string $dateField
     * @param string|null $selectFields Campo de seleção de campos, sempre usar a letra *u* por exemplo u.name
     * @param string|null $beginDate
     * @param string|null $lastDate
     * @return array
     * @throws \Exception
     */
    protected function getGenericListByDate(
        string $repository,
        string $dateField = 'createDate',
        ?string $selectFields = 'u',
        ?string $beginDate = null,
        ?string $lastDate = null
    ): array {
        $repo = 'App\Entity\\' . $repository;
        $convertedBeginDate = (new StringConvertions())->strToDateString($beginDate);
        $convertedLastDate = (new StringConvertions())->strToDateString($lastDate);
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $result = null;
        $query = $queryBuilder->select($selectFields)->from($repo, 'u');
        if ($convertedBeginDate === null && $convertedLastDate === null) {
            $result = $query->orderBy('u.id', 'ASC');
        }

        if ($convertedBeginDate !== null && $convertedLastDate !== null) {
            $result = $query->where(sprintf('u.%s BETWEEN :begin AND :last', $dateField))
                ->setParameter('begin', $convertedBeginDate)
                ->setParameter('last', $convertedLastDate)
                ->orderBy('u.id', 'ASC');
        }

        if ($convertedBeginDate === null && $convertedLastDate !== null) {
            $result = $query->where(sprintf('u.%s <= :date', $dateField))
                ->setParameter('date', sprintf('%s 23:00:00', $convertedLastDate))
                ->orderBy('u.id', 'ASC');
        }

        if ($convertedBeginDate !== null && $convertedLastDate === null) {
            $result = $query->where(sprintf('u.%s >= :date', $dateField))
                ->setParameter('date', $convertedBeginDate)
                ->orderBy('u.id', 'ASC');
        }
        return $result->getQuery()->getResult();
    }
}
