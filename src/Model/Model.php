<?php declare(strict_types=1);
namespace App\Model;

use \Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Yaml\Yaml;

/**
 * Service Container for models
 */
abstract class Model
{
    /**
     * Entity Manger
     *
     * @var object
     */
    protected $em;

    protected $config;

    public function __construct(ObjectManager $entityManager)
    {
        $this->em = $entityManager;
        $this->config = Yaml::parse(file_get_contents(__DIR__.'/../Config/system-config.yaml'));
    }
}
