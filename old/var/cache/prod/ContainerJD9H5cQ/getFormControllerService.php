<?php

namespace ContainerJD9H5cQ;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getFormControllerService extends App_KernelProdContainer
{
    /*
     * Gets the public 'App\Controller\FormController' shared autowired service.
     *
     * @return \App\Controller\FormController
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Controller'.\DIRECTORY_SEPARATOR.'FormController.php';

        $container->services['App\\Controller\\FormController'] = $instance = new \App\Controller\FormController();

        $instance->setContainer(($container->privates['.service_locator.5nX7ca3'] ?? $container->get_ServiceLocator_5nX7ca3Service())->withContext('App\\Controller\\FormController', $container));

        return $instance;
    }
}
