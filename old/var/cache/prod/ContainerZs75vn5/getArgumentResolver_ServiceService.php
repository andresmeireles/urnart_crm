<?php

namespace ContainerZs75vn5;

use Symfony\Component\DependencyInjection\Argument\RewindableGenerator;
use Symfony\Component\DependencyInjection\Exception\RuntimeException;

/*
 * @internal This class has been auto-generated by the Symfony Dependency Injection Component.
 */
class getArgumentResolver_ServiceService extends App_KernelProdContainer
{
    /*
     * Gets the private 'argument_resolver.service' shared service.
     *
     * @return \Symfony\Component\HttpKernel\Controller\ArgumentResolver\ServiceValueResolver
     */
    public static function do($container, $lazyLoad = true)
    {
        include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'symfony'.\DIRECTORY_SEPARATOR.'http-kernel'.\DIRECTORY_SEPARATOR.'Controller'.\DIRECTORY_SEPARATOR.'ArgumentValueResolverInterface.php';
        include_once \dirname(__DIR__, 4).''.\DIRECTORY_SEPARATOR.'vendor'.\DIRECTORY_SEPARATOR.'symfony'.\DIRECTORY_SEPARATOR.'http-kernel'.\DIRECTORY_SEPARATOR.'Controller'.\DIRECTORY_SEPARATOR.'ArgumentResolver'.\DIRECTORY_SEPARATOR.'ServiceValueResolver.php';

        return $container->privates['argument_resolver.service'] = new \Symfony\Component\HttpKernel\Controller\ArgumentResolver\ServiceValueResolver(new \Symfony\Component\DependencyInjection\Argument\ServiceLocator($container->getService, [
            'App\\Controller\\AdminController::addUser' => ['privates', '.service_locator.XE54fCb', 'get_ServiceLocator_XE54fCbService', true],
            'App\\Controller\\AdminController::asyncEditUserPermissions' => ['privates', '.service_locator.W8eszA3', 'get_ServiceLocator_W8eszA3Service', true],
            'App\\Controller\\AdminController::editUserPermissions' => ['privates', '.service_locator.W8eszA3', 'get_ServiceLocator_W8eszA3Service', true],
            'App\\Controller\\BoletoReportController::boletoChangeStatus' => ['privates', '.service_locator.ngJRwmO', 'get_ServiceLocator_NgJRwmOService', true],
            'App\\Controller\\BoletoReportController::createBoletoReport' => ['privates', '.service_locator.ngJRwmO', 'get_ServiceLocator_NgJRwmOService', true],
            'App\\Controller\\FormController::editTravelAccountability' => ['privates', '.service_locator.O.M60No', 'get_ServiceLocator_O_M60NoService', true],
            'App\\Controller\\FormController::findFormTemplate' => ['privates', '.service_locator.7uQ4J.O', 'get_ServiceLocator_7uQ4J_OService', true],
            'App\\Controller\\FormController::printForm' => ['privates', '.service_locator.xI58_fC', 'get_ServiceLocator_XI58FCService', true],
            'App\\Controller\\FormController::renderRomaneioFormWithData' => ['privates', '.service_locator.7uQ4J.O', 'get_ServiceLocator_7uQ4J_OService', true],
            'App\\Controller\\FormController::saveFormOnDb' => ['privates', '.service_locator.O.M60No', 'get_ServiceLocator_O_M60NoService', true],
            'App\\Controller\\FormController::saveFunction' => ['privates', '.service_locator.svT6Ko0', 'get_ServiceLocator_SvT6Ko0Service', true],
            'App\\Controller\\FormController::sendPdfForm' => ['privates', '.service_locator.xI58_fC', 'get_ServiceLocator_XI58FCService', true],
            'App\\Controller\\FormController::terminateTravel' => ['privates', '.service_locator.xI58_fC', 'get_ServiceLocator_XI58FCService', true],
            'App\\Controller\\FormController::viewSaveReports' => ['privates', '.service_locator.7uQ4J.O', 'get_ServiceLocator_7uQ4J_OService', true],
            'App\\Controller\\HomeController::index' => ['privates', '.service_locator.QkMsaYk', 'get_ServiceLocator_QkMsaYkService', true],
            'App\\Controller\\ManualOrderController::closeManualOrder' => ['privates', '.service_locator.zbl6eRt', 'get_ServiceLocator_Zbl6eRtService', true],
            'App\\Controller\\ManualOrderController::createManualOrderAsync' => ['privates', '.service_locator._HPMjRe', 'get_ServiceLocator_HPMjReService', true],
            'App\\Controller\\ManualOrderController::editManualOrderAsync' => ['privates', '.service_locator._HPMjRe', 'get_ServiceLocator_HPMjReService', true],
            'App\\Controller\\ManualOrderController::manualListing' => ['privates', '.service_locator.bDgxiNu', 'get_ServiceLocator_BDgxiNuService', true],
            'App\\Controller\\ManualOrderController::manualListingJson' => ['privates', '.service_locator.6B5TqXN', 'get_ServiceLocator_6B5TqXNService', true],
            'App\\Controller\\OrderController::createOrder' => ['privates', '.service_locator.xxWNieh', 'get_ServiceLocator_XxWNiehService', true],
            'App\\Controller\\OrderController::index' => ['privates', '.service_locator.tfOuKrV', 'get_ServiceLocator_TfOuKrVService', true],
            'App\\Controller\\OrderController::list' => ['privates', '.service_locator.QkMsaYk', 'get_ServiceLocator_QkMsaYkService', true],
            'App\\Controller\\OrderController::redirectOrderActions' => ['privates', '.service_locator.xxWNieh', 'get_ServiceLocator_XxWNiehService', true],
            'App\\Controller\\OrderController::removeOrder' => ['privates', '.service_locator.xxWNieh', 'get_ServiceLocator_XxWNiehService', true],
            'App\\Controller\\OrderController::simpleSearchEngine' => ['privates', '.service_locator.tfOuKrV', 'get_ServiceLocator_TfOuKrVService', true],
            'App\\Controller\\PredictionController::definePrediction' => ['privates', '.service_locator.4HM589G', 'get_ServiceLocator_4HM589GService', true],
            'App\\Controller\\PredictionController::order' => ['privates', '.service_locator.4HM589G', 'get_ServiceLocator_4HM589GService', true],
            'App\\Controller\\ProductionCountController::buildMonthReport' => ['privates', '.service_locator.jGexZO8', 'get_ServiceLocator_JGexZO8Service', true],
            'App\\Controller\\ProductionCountController::createProductionCountReportByDate' => ['privates', '.service_locator.uh7zYdB', 'get_ServiceLocator_Uh7zYdBService', true],
            'App\\Controller\\ProfileController::changePassword' => ['privates', '.service_locator.XE54fCb', 'get_ServiceLocator_XE54fCbService', true],
            'App\\Controller\\ProfileController::editUser' => ['privates', '.service_locator.W8eszA3', 'get_ServiceLocator_W8eszA3Service', true],
            'App\\Controller\\ProfileController::resetImage' => ['privates', '.service_locator.W8eszA3', 'get_ServiceLocator_W8eszA3Service', true],
            'App\\Controller\\RegisterController::addGenericRegister' => ['privates', '.service_locator.j42OHEG', 'get_ServiceLocator_J42OHEGService', true],
            'App\\Controller\\RegisterController::addGenericRegisterAjax' => ['privates', '.service_locator.j42OHEG', 'get_ServiceLocator_J42OHEGService', true],
            'App\\Controller\\RegisterController::addModelRegister' => ['privates', '.service_locator.gCAOgR3', 'get_ServiceLocator_GCAOgR3Service', true],
            'App\\Controller\\RegisterController::getGenericRegister' => ['privates', '.service_locator.QSeM5Y8', 'get_ServiceLocator_QSeM5Y8Service', true],
            'App\\Controller\\RegisterController::getGenericRemover' => ['privates', '.service_locator.y5Jcv4Y', 'get_ServiceLocator_Y5Jcv4YService', true],
            'App\\Controller\\RegisterController::getProductPriceById' => ['privates', '.service_locator.QSeM5Y8', 'get_ServiceLocator_QSeM5Y8Service', true],
            'App\\Controller\\RegisterController::getRegisterWithSimpleCriteria' => ['privates', '.service_locator.QSeM5Y8', 'get_ServiceLocator_QSeM5Y8Service', true],
            'App\\Controller\\RegisterController::getSingleRegiterById' => ['privates', '.service_locator.QSeM5Y8', 'get_ServiceLocator_QSeM5Y8Service', true],
            'App\\Controller\\ReportController::createByCatchModel' => ['privates', '.service_locator.PR5Iskg', 'get_ServiceLocator_PR5IskgService', true],
            'App\\Controller\\ReportController::createGenericReportRegistry' => ['privates', '.service_locator._Pdnj_h', 'get_ServiceLocator_PdnjHService', true],
            'App\\Controller\\ReportController::createGenericReportRegistryAjax' => ['privates', '.service_locator._Pdnj_h', 'get_ServiceLocator_PdnjHService', true],
            'App\\Controller\\ReportController::createSurvey' => ['privates', '.service_locator.6ceFqBY', 'get_ServiceLocator_6ceFqBYService', true],
            'App\\Controller\\ReportController::getGenericList' => ['privates', '.service_locator._Pdnj_h', 'get_ServiceLocator_PdnjHService', true],
            'App\\Controller\\ReportController::getSingleDataGenericAjax' => ['privates', '.service_locator._Pdnj_h', 'get_ServiceLocator_PdnjHService', true],
            'App\\Controller\\ReportController::makePrintReport' => ['privates', '.service_locator.t3qfEd9', 'get_ServiceLocator_T3qfEd9Service', true],
            'App\\Controller\\ReportController::makeRepo' => ['privates', '.service_locator.t3qfEd9', 'get_ServiceLocator_T3qfEd9Service', true],
            'App\\Controller\\ReportController::openProductionCountReportIndexPage' => ['privates', '.service_locator.RbxL4vS', 'get_ServiceLocator_RbxL4vSService', true],
            'App\\Controller\\ReportController::printAllProductionBalanceReport' => ['privates', '.service_locator.t3qfEd9', 'get_ServiceLocator_T3qfEd9Service', true],
            'App\\Controller\\ReportController::sellMonthReport' => ['privates', '.service_locator.NblINS4', 'get_ServiceLocator_NblINS4Service', true],
            'App\\Controller\\ReportController::sellReportWithRange' => ['privates', '.service_locator.jhkaX4z', 'get_ServiceLocator_JhkaX4zService', true],
            'App\\Controller\\ReportController::sendSurveys' => ['privates', '.service_locator.6ceFqBY', 'get_ServiceLocator_6ceFqBYService', true],
            'App\\Controller\\ReportController::viewEditGeneric' => ['privates', '.service_locator._Pdnj_h', 'get_ServiceLocator_PdnjHService', true],
            'App\\Controller\\SecurityController::login' => ['privates', '.service_locator.UDgw6Ol', 'get_ServiceLocator_UDgw6OlService', true],
            'App\\Controller\\TravelAccountabilityController::createtruckArrivalAccountabilityReport' => ['privates', '.service_locator.pevfr_f', 'get_ServiceLocator_PevfrFService', true],
            'App\\Controller\\TravelAccountabilityController::editAccountabilityReport' => ['privates', '.service_locator.pevfr_f', 'get_ServiceLocator_PevfrFService', true],
            'App\\Controller\\TravelAccountabilityController::finishAccountabilityReport' => ['privates', '.service_locator.pevfr_f', 'get_ServiceLocator_PevfrFService', true],
            'App\\Controller\\TravelAccountabilityController::printTravelAccountabilityReport' => ['privates', '.service_locator.xI58_fC', 'get_ServiceLocator_XI58FCService', true],
            'App\\Controller\\TruckController::createModelsNamesReport' => ['privates', '.service_locator.rftE_QG', 'get_ServiceLocator_RftEQGService', true],
            'App\\Controller\\TruckController::createPdfReport' => ['privates', '.service_locator.krI33ge', 'get_ServiceLocator_KrI33geService', true],
            'App\\Controller\\TruckController::createSingleReports' => ['privates', '.service_locator.krI33ge', 'get_ServiceLocator_KrI33geService', true],
            'App\\Controller\\TruckController::createTruckOrderForm' => ['privates', '.service_locator.O3MQCCg', 'get_ServiceLocator_O3MQCCgService', true],
            'App\\Controller\\TruckController::editTruckOrderReport' => ['privates', '.service_locator.O3MQCCg', 'get_ServiceLocator_O3MQCCgService', true],
            'App\\Controller\\TruckController::getAllReportsInZipFile' => ['privates', '.service_locator.LqGve25', 'get_ServiceLocator_LqGve25Service', true],
            'App\\Controller\\AdminController:addUser' => ['privates', '.service_locator.XE54fCb', 'get_ServiceLocator_XE54fCbService', true],
            'App\\Controller\\AdminController:asyncEditUserPermissions' => ['privates', '.service_locator.W8eszA3', 'get_ServiceLocator_W8eszA3Service', true],
            'App\\Controller\\AdminController:editUserPermissions' => ['privates', '.service_locator.W8eszA3', 'get_ServiceLocator_W8eszA3Service', true],
            'App\\Controller\\BoletoReportController:boletoChangeStatus' => ['privates', '.service_locator.ngJRwmO', 'get_ServiceLocator_NgJRwmOService', true],
            'App\\Controller\\BoletoReportController:createBoletoReport' => ['privates', '.service_locator.ngJRwmO', 'get_ServiceLocator_NgJRwmOService', true],
            'App\\Controller\\FormController:editTravelAccountability' => ['privates', '.service_locator.O.M60No', 'get_ServiceLocator_O_M60NoService', true],
            'App\\Controller\\FormController:findFormTemplate' => ['privates', '.service_locator.7uQ4J.O', 'get_ServiceLocator_7uQ4J_OService', true],
            'App\\Controller\\FormController:printForm' => ['privates', '.service_locator.xI58_fC', 'get_ServiceLocator_XI58FCService', true],
            'App\\Controller\\FormController:renderRomaneioFormWithData' => ['privates', '.service_locator.7uQ4J.O', 'get_ServiceLocator_7uQ4J_OService', true],
            'App\\Controller\\FormController:saveFormOnDb' => ['privates', '.service_locator.O.M60No', 'get_ServiceLocator_O_M60NoService', true],
            'App\\Controller\\FormController:saveFunction' => ['privates', '.service_locator.svT6Ko0', 'get_ServiceLocator_SvT6Ko0Service', true],
            'App\\Controller\\FormController:sendPdfForm' => ['privates', '.service_locator.xI58_fC', 'get_ServiceLocator_XI58FCService', true],
            'App\\Controller\\FormController:terminateTravel' => ['privates', '.service_locator.xI58_fC', 'get_ServiceLocator_XI58FCService', true],
            'App\\Controller\\FormController:viewSaveReports' => ['privates', '.service_locator.7uQ4J.O', 'get_ServiceLocator_7uQ4J_OService', true],
            'App\\Controller\\HomeController:index' => ['privates', '.service_locator.QkMsaYk', 'get_ServiceLocator_QkMsaYkService', true],
            'App\\Controller\\ManualOrderController:closeManualOrder' => ['privates', '.service_locator.zbl6eRt', 'get_ServiceLocator_Zbl6eRtService', true],
            'App\\Controller\\ManualOrderController:createManualOrderAsync' => ['privates', '.service_locator._HPMjRe', 'get_ServiceLocator_HPMjReService', true],
            'App\\Controller\\ManualOrderController:editManualOrderAsync' => ['privates', '.service_locator._HPMjRe', 'get_ServiceLocator_HPMjReService', true],
            'App\\Controller\\ManualOrderController:manualListing' => ['privates', '.service_locator.bDgxiNu', 'get_ServiceLocator_BDgxiNuService', true],
            'App\\Controller\\ManualOrderController:manualListingJson' => ['privates', '.service_locator.6B5TqXN', 'get_ServiceLocator_6B5TqXNService', true],
            'App\\Controller\\OrderController:createOrder' => ['privates', '.service_locator.xxWNieh', 'get_ServiceLocator_XxWNiehService', true],
            'App\\Controller\\OrderController:index' => ['privates', '.service_locator.tfOuKrV', 'get_ServiceLocator_TfOuKrVService', true],
            'App\\Controller\\OrderController:list' => ['privates', '.service_locator.QkMsaYk', 'get_ServiceLocator_QkMsaYkService', true],
            'App\\Controller\\OrderController:redirectOrderActions' => ['privates', '.service_locator.xxWNieh', 'get_ServiceLocator_XxWNiehService', true],
            'App\\Controller\\OrderController:removeOrder' => ['privates', '.service_locator.xxWNieh', 'get_ServiceLocator_XxWNiehService', true],
            'App\\Controller\\OrderController:simpleSearchEngine' => ['privates', '.service_locator.tfOuKrV', 'get_ServiceLocator_TfOuKrVService', true],
            'App\\Controller\\PredictionController:definePrediction' => ['privates', '.service_locator.4HM589G', 'get_ServiceLocator_4HM589GService', true],
            'App\\Controller\\PredictionController:order' => ['privates', '.service_locator.4HM589G', 'get_ServiceLocator_4HM589GService', true],
            'App\\Controller\\ProductionCountController:buildMonthReport' => ['privates', '.service_locator.jGexZO8', 'get_ServiceLocator_JGexZO8Service', true],
            'App\\Controller\\ProductionCountController:createProductionCountReportByDate' => ['privates', '.service_locator.uh7zYdB', 'get_ServiceLocator_Uh7zYdBService', true],
            'App\\Controller\\ProfileController:changePassword' => ['privates', '.service_locator.XE54fCb', 'get_ServiceLocator_XE54fCbService', true],
            'App\\Controller\\ProfileController:editUser' => ['privates', '.service_locator.W8eszA3', 'get_ServiceLocator_W8eszA3Service', true],
            'App\\Controller\\ProfileController:resetImage' => ['privates', '.service_locator.W8eszA3', 'get_ServiceLocator_W8eszA3Service', true],
            'App\\Controller\\RegisterController:addGenericRegister' => ['privates', '.service_locator.j42OHEG', 'get_ServiceLocator_J42OHEGService', true],
            'App\\Controller\\RegisterController:addGenericRegisterAjax' => ['privates', '.service_locator.j42OHEG', 'get_ServiceLocator_J42OHEGService', true],
            'App\\Controller\\RegisterController:addModelRegister' => ['privates', '.service_locator.gCAOgR3', 'get_ServiceLocator_GCAOgR3Service', true],
            'App\\Controller\\RegisterController:getGenericRegister' => ['privates', '.service_locator.QSeM5Y8', 'get_ServiceLocator_QSeM5Y8Service', true],
            'App\\Controller\\RegisterController:getGenericRemover' => ['privates', '.service_locator.y5Jcv4Y', 'get_ServiceLocator_Y5Jcv4YService', true],
            'App\\Controller\\RegisterController:getProductPriceById' => ['privates', '.service_locator.QSeM5Y8', 'get_ServiceLocator_QSeM5Y8Service', true],
            'App\\Controller\\RegisterController:getRegisterWithSimpleCriteria' => ['privates', '.service_locator.QSeM5Y8', 'get_ServiceLocator_QSeM5Y8Service', true],
            'App\\Controller\\RegisterController:getSingleRegiterById' => ['privates', '.service_locator.QSeM5Y8', 'get_ServiceLocator_QSeM5Y8Service', true],
            'App\\Controller\\ReportController:createByCatchModel' => ['privates', '.service_locator.PR5Iskg', 'get_ServiceLocator_PR5IskgService', true],
            'App\\Controller\\ReportController:createGenericReportRegistry' => ['privates', '.service_locator._Pdnj_h', 'get_ServiceLocator_PdnjHService', true],
            'App\\Controller\\ReportController:createGenericReportRegistryAjax' => ['privates', '.service_locator._Pdnj_h', 'get_ServiceLocator_PdnjHService', true],
            'App\\Controller\\ReportController:createSurvey' => ['privates', '.service_locator.6ceFqBY', 'get_ServiceLocator_6ceFqBYService', true],
            'App\\Controller\\ReportController:getGenericList' => ['privates', '.service_locator._Pdnj_h', 'get_ServiceLocator_PdnjHService', true],
            'App\\Controller\\ReportController:getSingleDataGenericAjax' => ['privates', '.service_locator._Pdnj_h', 'get_ServiceLocator_PdnjHService', true],
            'App\\Controller\\ReportController:makePrintReport' => ['privates', '.service_locator.t3qfEd9', 'get_ServiceLocator_T3qfEd9Service', true],
            'App\\Controller\\ReportController:makeRepo' => ['privates', '.service_locator.t3qfEd9', 'get_ServiceLocator_T3qfEd9Service', true],
            'App\\Controller\\ReportController:openProductionCountReportIndexPage' => ['privates', '.service_locator.RbxL4vS', 'get_ServiceLocator_RbxL4vSService', true],
            'App\\Controller\\ReportController:printAllProductionBalanceReport' => ['privates', '.service_locator.t3qfEd9', 'get_ServiceLocator_T3qfEd9Service', true],
            'App\\Controller\\ReportController:sellMonthReport' => ['privates', '.service_locator.NblINS4', 'get_ServiceLocator_NblINS4Service', true],
            'App\\Controller\\ReportController:sellReportWithRange' => ['privates', '.service_locator.jhkaX4z', 'get_ServiceLocator_JhkaX4zService', true],
            'App\\Controller\\ReportController:sendSurveys' => ['privates', '.service_locator.6ceFqBY', 'get_ServiceLocator_6ceFqBYService', true],
            'App\\Controller\\ReportController:viewEditGeneric' => ['privates', '.service_locator._Pdnj_h', 'get_ServiceLocator_PdnjHService', true],
            'App\\Controller\\SecurityController:login' => ['privates', '.service_locator.UDgw6Ol', 'get_ServiceLocator_UDgw6OlService', true],
            'App\\Controller\\TravelAccountabilityController:createtruckArrivalAccountabilityReport' => ['privates', '.service_locator.pevfr_f', 'get_ServiceLocator_PevfrFService', true],
            'App\\Controller\\TravelAccountabilityController:editAccountabilityReport' => ['privates', '.service_locator.pevfr_f', 'get_ServiceLocator_PevfrFService', true],
            'App\\Controller\\TravelAccountabilityController:finishAccountabilityReport' => ['privates', '.service_locator.pevfr_f', 'get_ServiceLocator_PevfrFService', true],
            'App\\Controller\\TravelAccountabilityController:printTravelAccountabilityReport' => ['privates', '.service_locator.xI58_fC', 'get_ServiceLocator_XI58FCService', true],
            'App\\Controller\\TruckController:createModelsNamesReport' => ['privates', '.service_locator.rftE_QG', 'get_ServiceLocator_RftEQGService', true],
            'App\\Controller\\TruckController:createPdfReport' => ['privates', '.service_locator.krI33ge', 'get_ServiceLocator_KrI33geService', true],
            'App\\Controller\\TruckController:createSingleReports' => ['privates', '.service_locator.krI33ge', 'get_ServiceLocator_KrI33geService', true],
            'App\\Controller\\TruckController:createTruckOrderForm' => ['privates', '.service_locator.O3MQCCg', 'get_ServiceLocator_O3MQCCgService', true],
            'App\\Controller\\TruckController:editTruckOrderReport' => ['privates', '.service_locator.O3MQCCg', 'get_ServiceLocator_O3MQCCgService', true],
            'App\\Controller\\TruckController:getAllReportsInZipFile' => ['privates', '.service_locator.LqGve25', 'get_ServiceLocator_LqGve25Service', true],
        ], [
            'App\\Controller\\AdminController::addUser' => '?',
            'App\\Controller\\AdminController::asyncEditUserPermissions' => '?',
            'App\\Controller\\AdminController::editUserPermissions' => '?',
            'App\\Controller\\BoletoReportController::boletoChangeStatus' => '?',
            'App\\Controller\\BoletoReportController::createBoletoReport' => '?',
            'App\\Controller\\FormController::editTravelAccountability' => '?',
            'App\\Controller\\FormController::findFormTemplate' => '?',
            'App\\Controller\\FormController::printForm' => '?',
            'App\\Controller\\FormController::renderRomaneioFormWithData' => '?',
            'App\\Controller\\FormController::saveFormOnDb' => '?',
            'App\\Controller\\FormController::saveFunction' => '?',
            'App\\Controller\\FormController::sendPdfForm' => '?',
            'App\\Controller\\FormController::terminateTravel' => '?',
            'App\\Controller\\FormController::viewSaveReports' => '?',
            'App\\Controller\\HomeController::index' => '?',
            'App\\Controller\\ManualOrderController::closeManualOrder' => '?',
            'App\\Controller\\ManualOrderController::createManualOrderAsync' => '?',
            'App\\Controller\\ManualOrderController::editManualOrderAsync' => '?',
            'App\\Controller\\ManualOrderController::manualListing' => '?',
            'App\\Controller\\ManualOrderController::manualListingJson' => '?',
            'App\\Controller\\OrderController::createOrder' => '?',
            'App\\Controller\\OrderController::index' => '?',
            'App\\Controller\\OrderController::list' => '?',
            'App\\Controller\\OrderController::redirectOrderActions' => '?',
            'App\\Controller\\OrderController::removeOrder' => '?',
            'App\\Controller\\OrderController::simpleSearchEngine' => '?',
            'App\\Controller\\PredictionController::definePrediction' => '?',
            'App\\Controller\\PredictionController::order' => '?',
            'App\\Controller\\ProductionCountController::buildMonthReport' => '?',
            'App\\Controller\\ProductionCountController::createProductionCountReportByDate' => '?',
            'App\\Controller\\ProfileController::changePassword' => '?',
            'App\\Controller\\ProfileController::editUser' => '?',
            'App\\Controller\\ProfileController::resetImage' => '?',
            'App\\Controller\\RegisterController::addGenericRegister' => '?',
            'App\\Controller\\RegisterController::addGenericRegisterAjax' => '?',
            'App\\Controller\\RegisterController::addModelRegister' => '?',
            'App\\Controller\\RegisterController::getGenericRegister' => '?',
            'App\\Controller\\RegisterController::getGenericRemover' => '?',
            'App\\Controller\\RegisterController::getProductPriceById' => '?',
            'App\\Controller\\RegisterController::getRegisterWithSimpleCriteria' => '?',
            'App\\Controller\\RegisterController::getSingleRegiterById' => '?',
            'App\\Controller\\ReportController::createByCatchModel' => '?',
            'App\\Controller\\ReportController::createGenericReportRegistry' => '?',
            'App\\Controller\\ReportController::createGenericReportRegistryAjax' => '?',
            'App\\Controller\\ReportController::createSurvey' => '?',
            'App\\Controller\\ReportController::getGenericList' => '?',
            'App\\Controller\\ReportController::getSingleDataGenericAjax' => '?',
            'App\\Controller\\ReportController::makePrintReport' => '?',
            'App\\Controller\\ReportController::makeRepo' => '?',
            'App\\Controller\\ReportController::openProductionCountReportIndexPage' => '?',
            'App\\Controller\\ReportController::printAllProductionBalanceReport' => '?',
            'App\\Controller\\ReportController::sellMonthReport' => '?',
            'App\\Controller\\ReportController::sellReportWithRange' => '?',
            'App\\Controller\\ReportController::sendSurveys' => '?',
            'App\\Controller\\ReportController::viewEditGeneric' => '?',
            'App\\Controller\\SecurityController::login' => '?',
            'App\\Controller\\TravelAccountabilityController::createtruckArrivalAccountabilityReport' => '?',
            'App\\Controller\\TravelAccountabilityController::editAccountabilityReport' => '?',
            'App\\Controller\\TravelAccountabilityController::finishAccountabilityReport' => '?',
            'App\\Controller\\TravelAccountabilityController::printTravelAccountabilityReport' => '?',
            'App\\Controller\\TruckController::createModelsNamesReport' => '?',
            'App\\Controller\\TruckController::createPdfReport' => '?',
            'App\\Controller\\TruckController::createSingleReports' => '?',
            'App\\Controller\\TruckController::createTruckOrderForm' => '?',
            'App\\Controller\\TruckController::editTruckOrderReport' => '?',
            'App\\Controller\\TruckController::getAllReportsInZipFile' => '?',
            'App\\Controller\\AdminController:addUser' => '?',
            'App\\Controller\\AdminController:asyncEditUserPermissions' => '?',
            'App\\Controller\\AdminController:editUserPermissions' => '?',
            'App\\Controller\\BoletoReportController:boletoChangeStatus' => '?',
            'App\\Controller\\BoletoReportController:createBoletoReport' => '?',
            'App\\Controller\\FormController:editTravelAccountability' => '?',
            'App\\Controller\\FormController:findFormTemplate' => '?',
            'App\\Controller\\FormController:printForm' => '?',
            'App\\Controller\\FormController:renderRomaneioFormWithData' => '?',
            'App\\Controller\\FormController:saveFormOnDb' => '?',
            'App\\Controller\\FormController:saveFunction' => '?',
            'App\\Controller\\FormController:sendPdfForm' => '?',
            'App\\Controller\\FormController:terminateTravel' => '?',
            'App\\Controller\\FormController:viewSaveReports' => '?',
            'App\\Controller\\HomeController:index' => '?',
            'App\\Controller\\ManualOrderController:closeManualOrder' => '?',
            'App\\Controller\\ManualOrderController:createManualOrderAsync' => '?',
            'App\\Controller\\ManualOrderController:editManualOrderAsync' => '?',
            'App\\Controller\\ManualOrderController:manualListing' => '?',
            'App\\Controller\\ManualOrderController:manualListingJson' => '?',
            'App\\Controller\\OrderController:createOrder' => '?',
            'App\\Controller\\OrderController:index' => '?',
            'App\\Controller\\OrderController:list' => '?',
            'App\\Controller\\OrderController:redirectOrderActions' => '?',
            'App\\Controller\\OrderController:removeOrder' => '?',
            'App\\Controller\\OrderController:simpleSearchEngine' => '?',
            'App\\Controller\\PredictionController:definePrediction' => '?',
            'App\\Controller\\PredictionController:order' => '?',
            'App\\Controller\\ProductionCountController:buildMonthReport' => '?',
            'App\\Controller\\ProductionCountController:createProductionCountReportByDate' => '?',
            'App\\Controller\\ProfileController:changePassword' => '?',
            'App\\Controller\\ProfileController:editUser' => '?',
            'App\\Controller\\ProfileController:resetImage' => '?',
            'App\\Controller\\RegisterController:addGenericRegister' => '?',
            'App\\Controller\\RegisterController:addGenericRegisterAjax' => '?',
            'App\\Controller\\RegisterController:addModelRegister' => '?',
            'App\\Controller\\RegisterController:getGenericRegister' => '?',
            'App\\Controller\\RegisterController:getGenericRemover' => '?',
            'App\\Controller\\RegisterController:getProductPriceById' => '?',
            'App\\Controller\\RegisterController:getRegisterWithSimpleCriteria' => '?',
            'App\\Controller\\RegisterController:getSingleRegiterById' => '?',
            'App\\Controller\\ReportController:createByCatchModel' => '?',
            'App\\Controller\\ReportController:createGenericReportRegistry' => '?',
            'App\\Controller\\ReportController:createGenericReportRegistryAjax' => '?',
            'App\\Controller\\ReportController:createSurvey' => '?',
            'App\\Controller\\ReportController:getGenericList' => '?',
            'App\\Controller\\ReportController:getSingleDataGenericAjax' => '?',
            'App\\Controller\\ReportController:makePrintReport' => '?',
            'App\\Controller\\ReportController:makeRepo' => '?',
            'App\\Controller\\ReportController:openProductionCountReportIndexPage' => '?',
            'App\\Controller\\ReportController:printAllProductionBalanceReport' => '?',
            'App\\Controller\\ReportController:sellMonthReport' => '?',
            'App\\Controller\\ReportController:sellReportWithRange' => '?',
            'App\\Controller\\ReportController:sendSurveys' => '?',
            'App\\Controller\\ReportController:viewEditGeneric' => '?',
            'App\\Controller\\SecurityController:login' => '?',
            'App\\Controller\\TravelAccountabilityController:createtruckArrivalAccountabilityReport' => '?',
            'App\\Controller\\TravelAccountabilityController:editAccountabilityReport' => '?',
            'App\\Controller\\TravelAccountabilityController:finishAccountabilityReport' => '?',
            'App\\Controller\\TravelAccountabilityController:printTravelAccountabilityReport' => '?',
            'App\\Controller\\TruckController:createModelsNamesReport' => '?',
            'App\\Controller\\TruckController:createPdfReport' => '?',
            'App\\Controller\\TruckController:createSingleReports' => '?',
            'App\\Controller\\TruckController:createTruckOrderForm' => '?',
            'App\\Controller\\TruckController:editTruckOrderReport' => '?',
            'App\\Controller\\TruckController:getAllReportsInZipFile' => '?',
        ]));
    }
}
