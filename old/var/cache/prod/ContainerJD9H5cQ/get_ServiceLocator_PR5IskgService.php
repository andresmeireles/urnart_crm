<?php

namespace ContainerJD9H5cQ;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_PR5IskgService extends App_KernelProdContainer
{
    /*
     * Gets the private '.service_locator.PR5Iskg' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.PR5Iskg'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'productionCountModel' => ['privates', 'App\\Model\\ProductionCountModel', 'getProductionCountModelService', true],
        ], [
            'productionCountModel' => 'App\\Model\\ProductionCountModel',
        ]);
    }
}
