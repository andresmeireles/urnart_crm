<?php

namespace ContainerZs75vn5;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_XxWNiehService extends App_KernelProdContainer
{
    /*
     * Gets the private '.service_locator.xxWNieh' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.xxWNieh'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'model' => ['privates', 'App\\Model\\OrderModel', 'getOrderModelService', true],
        ], [
            'model' => 'App\\Model\\OrderModel',
        ]);
    }
}
