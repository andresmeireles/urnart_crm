<?php

namespace ContainerJD9H5cQ;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getDoctrineMongodb_Odm_DefaultConnectionService extends App_KernelProdContainer
{
    /*
     * Gets the public 'doctrine_mongodb.odm.default_connection' shared service.
     *
     * @return \MongoDB\Client
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'mongodb'.\DIRECTORY_SEPARATOR.'mongodb'.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Client.php';

        return $container->services['doctrine_mongodb.odm.default_connection'] = new \MongoDB\Client($container->getEnv('resolve:MONGODB_URL'), [], ['typeMap' => ['root' => 'array', 'document' => 'array']]);
    }
}
