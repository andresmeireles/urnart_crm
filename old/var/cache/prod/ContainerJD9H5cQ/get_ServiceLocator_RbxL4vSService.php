<?php

namespace ContainerJD9H5cQ;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_RbxL4vSService extends App_KernelProdContainer
{
    /*
     * Gets the private '.service_locator.RbxL4vS' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.RbxL4vS'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'model' => ['privates', 'App\\Model\\ProductionCountModel', 'getProductionCountModelService', true],
            'productionCountRepository' => ['privates', 'App\\Repository\\ProductionCountRepository', 'getProductionCountRepositoryService', true],
        ], [
            'model' => 'App\\Model\\ProductionCountModel',
            'productionCountRepository' => 'App\\Repository\\ProductionCountRepository',
        ]);
    }
}
