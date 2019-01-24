<?php declare(strict_types=1);
namespace App\Model;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\AbstractManagerRegistry;
use Doctrine\DBAL\Connection;
use Symfony\Component\Yaml\Yaml;
use App\Config\NonStaticConfig;

/**
 * Service Container for models
 */
abstract class Model
{
    /**
     * Entity Manger
     *
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

    public function __construct(ObjectManager $entityManager)
    {
        $this->em = $entityManager;
        $this->config = Yaml::parse(__DIR__.'/../Config/system-config.yaml');
        $this->settings = new NonStaticConfig();
    }
}
