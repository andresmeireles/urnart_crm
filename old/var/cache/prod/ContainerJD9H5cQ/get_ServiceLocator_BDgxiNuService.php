<?php

namespace ContainerJD9H5cQ;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class get_ServiceLocator_BDgxiNuService extends App_KernelProdContainer
{
    /*
     * Gets the private '.service_locator.bDgxiNu' shared service.
     *
     * @return \Symfony\Component\DependencyInjection\ServiceLocator
     */
    public static function do($container, $lazyLoad = true)
    {
        return $container->privates['.service_locator.bDgxiNu'] = new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'listModel' => ['privates', 'App\\Model\\ListModel', 'getListModelService', true],
            'paginator' => ['services', 'knp_paginator', 'getKnpPaginatorService', true],
        ], [
            'listModel' => 'App\\Model\\ListModel',
            'paginator' => '?',
        ]);
    }
}
