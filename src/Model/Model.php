<?php declare(strict_types=1);
namespace App\Model;

use \Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\AbstractManagerRegistry;
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
     * @var AbstractManagerRegistry
     */
    protected $em;

    /**
     * Array with config parameters
     *
     * @var array
     */
    protected $config;

    protected $settings;

    public function __construct(AbstractManagerRegistry $entityManager)
    {
        $this->em = $entityManager;
        $this->config = Yaml::parse(__DIR__.'/../Config/system-config.yaml');
        $this->settings = new NonStaticConfig();
    }
}
