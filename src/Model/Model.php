<?php declare(strict_types=1);
namespace App\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\DBAL\Connection;
use Symfony\Component\Yaml\Yaml;
use App\Config\NonStaticConfig;
use JMS\Serializer\SerializerBuilder;
use JMS\Serializer\Metadata\ClassMetadata;
use App\Utils\Andresmei\StringConvertions;

/**
 * Service Container for models
 */
abstract class Model
{
    /**
     * Entity Manger
     *
     * @var Connection
     * @var ObjectManager
     */
    protected $em;

    /**
     * Array with config parameters
     *
     * @var array
     */
    protected $config;

    /**
     * Settings array
     *
     * @var NonStaticConfig
     */
    protected $settings;

    /**
     * Seralizator class
     *
     * @var ClassMetadata
     * @var SerializerBuilder
     */
    protected $serializer;

    public function __construct(ObjectManager $entityManager)
    {
        $this->em = $entityManager;
        $this->config = Yaml::parse(__DIR__.'/../Config/system-config.yaml');
        $this->settings = new NonStaticConfig();
        $this->serializer = SerializerBuilder::create()->build();
    }

    /**
     * Lista a partir de uma data dados de um repositorio.
     *
     * @param string        $repository
     * @param string        $dateField
     * @param string|null   $selectFields Campo de seleção de campos, sempre usar a letra *u* por exemplo u.name
     * @param string|null   $beginDate
     * @param string|null   $lastDate
     * @return array
     */
    protected function getGenericListByDate(
        string $repository, 
        string $dateField = 'createDate',
        ?string $selectFields = 'u', 
        ?string $beginDate, 
        ?string $lastDate): array
    {
        $repo = 'App\Entity\\'.$repository;
        $convertedBeginDate = (new StringConvertions())->strToDateString($beginDate);
        $convertedLastDate = (new StringConvertions())->strToDateString($lastDate);
        $queryBuilder = $this->em->createQueryBuilder();
        $result = null;
        $query = $queryBuilder->select($selectFields)->from($repo, 'u');
        if (is_null($convertedBeginDate) && is_null($convertedLastDate)) {
            $result = $query->orderBy('u.id', 'ASC');
        }

        if (!is_null($convertedBeginDate) && !is_null($convertedLastDate)) {
            $result = $query->where(sprintf('u.%s BETWEEN :begin AND :last', $dateField))
                            ->setParameter('begin', $convertedBeginDate)
                            ->setParameter('last', $convertedLastDate)
                            ->orderBy('u.id', 'ASC');
        }

        if (is_null($convertedBeginDate) && !is_null($convertedLastDate)) {
            $result = $query->where(sprintf('u.%s <= :date', $dateField))
                            ->setParameter('date', sprintf('%s 23:00:00', $convertedLastDate))
                            ->orderBy('u.id', 'ASC');
        }

        if (!is_null($convertedBeginDate) && is_null($convertedLastDate)) {
            $result = $query->where(sprintf('u.%s >= :date', $dateField))
                            ->setParameter('date', $convertedBeginDate)
                            ->orderBy('u.id', 'ASC');
        }
        return $result->getQuery()->getResult();
    }

    /**
     * Dql consult helper
     *
     * @param   string  $dqlStringConsult  String with consult in DQL.
     *
     * @return  array                      Array with results.
     */
    protected function dqlConsult(string $dqlStringConsult): array
    {
        $query = $this->em->createQuery($dqlStringConsult);
        $result = $query->execute();
        return $result;
    }

    protected function sqlConsult(string $sqlConsult)
    {
        $query = $this->em->createNativeQuery($sqlConsult);
        $result = $query->getResult();
        return $result;
    }
}
