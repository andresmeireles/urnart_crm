<?php

namespace ContainerZs75vn5;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_KrI33geService extends App_KernelProdContainer
{
    /*
     * Gets the private '.service_locator.krI33ge' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.krI33ge'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'departureModel' => ['privates', 'App\\Model\\DepartureModel', 'getDepartureModelService', true],
            'form' => ['privates', 'App\\Utils\\Andresmei\\Form', 'getFormService', true],
        ], [
            'departureModel' => 'App\\Model\\DepartureModel',
            'form' => 'App\\Utils\\Andresmei\\Form',
        ]);
    }
}
