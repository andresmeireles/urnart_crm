<?php

namespace ContainerJD9H5cQ;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getReportModelService extends App_KernelProdContainer
{
    /*
     * Gets the private 'App\Model\ReportModel' shared autowired service.
     *
     * @return \App\Model\ReportModel
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'src'.\DIRECTORY_SEPARATOR.'Model'.\DIRECTORY_SEPARATOR.'ReportModel.php';

        return $container->privates['App\\Model\\ReportModel'] = new \App\Model\ReportModel(($container->services['doctrine.orm.default_entity_manager'] ?? $container->getDoctrine_Orm_DefaultEntityManagerService()));
    }
}
