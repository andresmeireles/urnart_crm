<?php

namespace ContainerZs75vn5;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getTravelAccountabilityControllerService extends App_KernelProdContainer
{
    /*
     * Gets the public 'App\Controller\TravelAccountabilityController' shared autowired service.
     *
     * @return \App\Controller\TravelAccountabilityController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Controller'.\DIRECTORY_SEPARATOR.'CSRFTokenCheck.php';
        include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Controller'.\DIRECTORY_SEPARATOR.'TravelAccountabilityController.php';

        $container->services['App\\Controller\\TravelAccountabilityController'] = $instance = new \App\Controller\TravelAccountabilityController();

        $instance->setContainer(($container->privates['.service_locator.5nX7ca3'] ?? $container->get_ServiceLocator_5nX7ca3Service())->withContext('App\\Controller\\TravelAccountabilityController', $container));

        return $instance;
    }
}
