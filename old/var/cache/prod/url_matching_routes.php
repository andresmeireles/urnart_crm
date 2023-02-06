<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/admin' => [[['_route' => 'admin', '_controller' => 'App\\Controller\\AdminController::index'], null, null, null, false, false, null]],
        '/admin/user' => [[['_route' => 'user_page', '_controller' => 'App\\Controller\\AdminController::viewUserPage'], null, null, null, false, false, null]],
        '/admin/user/permission' => [
            [['_route' => 'user_permission', '_controller' => 'App\\Controller\\AdminController::viewUserPermission'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'user_permission_async', '_controller' => 'App\\Controller\\AdminController::editUserPermissions'], null, ['POST' => 0], null, false, false, null],
        ],
        '/api/admin/permission' => [[['_route' => 'app_admin_asyncedituserpermissions', '_controller' => 'App\\Controller\\AdminController::asyncEditUserPermissions'], null, ['POST' => 0], null, false, false, null]],
        '/admin/user/add' => [
            [['_route' => 'add_user', '_controller' => 'App\\Controller\\AdminController::viewAddUser'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'add_post_user', '_controller' => 'App\\Controller\\AdminController::addUser'], null, ['POST' => 0], null, false, false, null],
        ],
        '/boleto/index' => [[['_route' => 'app_boletoreport_renderboletoindexpage', '_controller' => 'App\\Controller\\BoletoReportController::renderBoletoIndexPage'], null, null, null, false, false, null]],
        '/forms' => [[['_route' => 'form', '_controller' => 'App\\Controller\\FormController::index'], null, null, null, false, false, null]],
        '/forms/view' => [[['_route' => 'form_view', '_controller' => 'App\\Controller\\FormController::viewSaveReports'], null, ['GET' => 0], null, false, false, null]],
        '/forms/save' => [[['_route' => 'save_report', '_controller' => 'App\\Controller\\FormController::saveFunction'], null, ['GET' => 0], null, false, false, null]],
        '/home' => [[['_route' => 'home', '_controller' => 'App\\Controller\\HomeController::index'], null, null, null, false, false, null]],
        '/' => [[['_route' => 'index', '_controller' => 'App\\Controller\\HomeController::index'], null, null, null, false, false, null]],
        '/order/manual/list' => [[['_route' => 'manualList', '_controller' => 'App\\Controller\\ManualOrderController::manualListing'], null, ['GET' => 0], null, false, false, null]],
        '/order/manual/list/json' => [[['_route' => 'manualListJson', '_controller' => 'App\\Controller\\ManualOrderController::manualListingJson'], null, null, null, false, false, null]],
        '/order/async/createManualOrder' => [[['_route' => 'async_create_manual_order', '_controller' => 'App\\Controller\\ManualOrderController::createManualOrderAsync'], null, ['POST' => 0], null, false, false, null]],
        '/order' => [[['_route' => 'order', '_controller' => 'App\\Controller\\OrderController::index'], null, null, null, false, false, null]],
        '/order/search' => [[['_route' => 'simple.search', '_controller' => 'App\\Controller\\OrderController::simpleSearchEngine'], null, ['GET' => 0], null, false, false, null]],
        '/order/list' => [[['_route' => 'app_order_list', 'type' => 'last', '_controller' => 'App\\Controller\\OrderController::list'], null, null, null, false, false, null]],
        '/order/create' => [[['_route' => 'createOrder', '_controller' => 'App\\Controller\\OrderController::createOrder'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/order/action/print' => [[['_route' => 'app_order_showordertoorder', '_controller' => 'App\\Controller\\OrderController::showOrderToOrder'], null, ['POST' => 0], null, false, false, null]],
        '/prediction' => [[['_route' => 'prediction', '_controller' => 'App\\Controller\\PredictionController::index'], null, null, null, false, false, null]],
        '/prediction/order' => [
            [['_route' => 'prediction_order', '_controller' => 'App\\Controller\\PredictionController::order'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'chanhe_prediction_date_order', '_controller' => 'App\\Controller\\PredictionController::definePrediction'], null, ['POST' => 0], null, false, false, null],
        ],
        '/production/count/report/bydate' => [[['_route' => 'prod_count_by_date', '_controller' => 'App\\Controller\\ProductionCountController::createProductionCountReportByDate'], null, ['POST' => 0], null, false, false, null]],
        '/production/count/report/month' => [[['_route' => 'month_report', '_controller' => 'App\\Controller\\ProductionCountController::buildMonthReport'], null, ['POST' => 0], null, false, false, null]],
        '/profile' => [[['_route' => 'profile', '_controller' => 'App\\Controller\\ProfileController::index'], null, null, null, false, false, null]],
        '/profile/edit' => [
            [['_route' => 'useredit', '_controller' => 'App\\Controller\\ProfileController::viewEdit'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_profile_edituser', '_controller' => 'App\\Controller\\ProfileController::editUser'], null, ['POST' => 0], null, false, false, null],
        ],
        '/profile/reset' => [[['_route' => 'app_profile_resetimage', '_controller' => 'App\\Controller\\ProfileController::resetImage'], null, ['POST' => 0], null, false, false, null]],
        '/profile/password' => [
            [['_route' => 'change_pass', '_controller' => 'App\\Controller\\ProfileController::viewPassword'], null, ['GET' => 0], null, false, false, null],
            [['_route' => 'app_profile_changepassword', '_controller' => 'App\\Controller\\ProfileController::changePassword'], null, ['POST' => 0], null, false, false, null],
        ],
        '/adminOverload' => [[['_route' => 'app_profile_overload', '_controller' => 'App\\Controller\\ProfileController::overload'], null, null, null, false, false, null]],
        '/register' => [[['_route' => 'register', '_controller' => 'App\\Controller\\RegisterController::index'], null, null, null, false, false, null]],
        '/register/add/model' => [[['_route' => 'app_register_addmodelregister', '_controller' => 'App\\Controller\\RegisterController::addModelRegister'], null, ['POST' => 0, 'PUT' => 1], null, false, false, null]],
        '/register/getProduct/price' => [[['_route' => 'get_registry', '_controller' => 'App\\Controller\\RegisterController::getProductPriceById'], null, ['POST' => 0], null, false, false, null]],
        '/report' => [[['_route' => 'report', '_controller' => 'App\\Controller\\ReportController::index'], null, null, null, false, false, null]],
        '/report/sells' => [[['_route' => 'report_sells', '_controller' => 'App\\Controller\\ReportController::viewSellReportForm'], null, ['GET' => 0], null, false, false, null]],
        '/report/sell/month' => [[['_route' => 'report_sells_month', '_controller' => 'App\\Controller\\ReportController::sellMonthReport'], null, ['POST' => 0], null, false, false, null]],
        '/report/sell/range' => [[['_route' => 'report_sells_range', '_controller' => 'App\\Controller\\ReportController::sellReportWithRange'], null, ['POST' => 0], null, false, false, null]],
        '/report/productionCount/mdr' => [[['_route' => 'make_report', '_controller' => 'App\\Controller\\ReportController::makePrintReport'], null, ['POST' => 0], null, false, false, null]],
        '/report/productionCount/mr' => [[['_route' => 'make_report_custom', '_controller' => 'App\\Controller\\ReportController::makeRepo'], null, ['POST' => 0], null, false, false, null]],
        '/report/productionCount' => [[['_route' => 'prod_count', '_controller' => 'App\\Controller\\ReportController::openProductionCountReportIndexPage'], null, ['GET' => 0], null, false, false, null]],
        '/report/productionCount/make_production_report' => [[['_route' => 'make_production_report', '_controller' => 'App\\Controller\\ReportController::makeProductionReport'], null, null, null, false, false, null]],
        '/report/productionCount/createByCatchModel' => [[['_route' => 'app_report_createbycatchmodel', '_controller' => 'App\\Controller\\ReportController::createByCatchModel'], null, ['POST' => 0], null, false, false, null]],
        '/get/ModelsModel' => [[['_route' => 'app_report_coiso', '_controller' => 'App\\Controller\\ReportController::coiso'], null, null, null, false, false, null]],
        '/report/create/survey' => [[['_route' => 'createSurvey', '_controller' => 'App\\Controller\\ReportController::createSurvey'], null, null, null, false, false, null]],
        '/report/survey/send' => [[['_route' => 'sendSurveys', '_controller' => 'App\\Controller\\ReportController::sendSurveys'], null, ['POST' => 0], null, false, false, null]],
        '/login' => [[['_route' => 'app_login', '_controller' => 'App\\Controller\\SecurityController::login'], null, null, null, false, false, null]],
        '/user' => [[['_route' => 'app_security_getusername', '_controller' => 'App\\Controller\\SecurityController::getUserName'], null, ['PATCH' => 0, 'POST' => 1], null, false, false, null]],
        '/travel/accountability/reports' => [[['_route' => 'accountability_show', '_controller' => 'App\\Controller\\TravelAccountabilityController::showAccoutabilityReports'], null, null, null, false, false, null]],
        '/truck' => [[['_route' => 'truck.index', '_controller' => 'App\\Controller\\TruckController::index'], null, null, null, false, false, null]],
        '/truck/create' => [[['_route' => 'create.truck.report', '_controller' => 'App\\Controller\\TruckController::createTruckOrderForm'], null, ['GET' => 0, 'POST' => 1], null, false, false, null]],
        '/truck/removeEv' => [[['_route' => 'app_truck_removeeverything', '_controller' => 'App\\Controller\\TruckController::removeEverything'], null, null, null, false, false, null]],
        '/logout' => [[['_route' => 'app_logout'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/re(?'
                    .'|port/(?'
                        .'|boleto/(?'
                            .'|status/([^/]++)(*:46)'
                            .'|reportCreator/([^/]++)(*:75)'
                        .')'
                        .'|([^/]++)(?'
                            .'|/(?'
                                .'|([^/]++)/print(*:112)'
                                .'|create(*:126)'
                                .'|list(*:138)'
                                .'|edit/(\\d+)(*:156)'
                            .')'
                            .'|(*:165)'
                        .')'
                    .')'
                    .'|gister/(?'
                        .'|add/([^/]++)(*:197)'
                        .'|get/(?'
                            .'|([^/]++)(*:220)'
                            .'|criteria/([^/]++)(*:245)'
                        .')'
                        .'|remove/([^/]++)(*:269)'
                    .')'
                .')'
                .'|/forms/(?'
                    .'|edit/romaneio/([^/]++)(*:311)'
                    .'|([^/]++)(?'
                        .'|(*:330)'
                        .'|/(?'
                            .'|print(?'
                                .'|(*:350)'
                                .'|/pdf(*:362)'
                            .')'
                            .'|(\\d+)/edit(*:381)'
                            .'|(\\d+)/terminate(*:404)'
                        .')'
                        .'|(*:413)'
                    .')'
                    .'|overlord/([^/]++)/([^/]++)(*:448)'
                .')'
                .'|/order/(?'
                    .'|manual/(?'
                        .'|(\\d+)(*:482)'
                        .'|status/(\\d+)(*:502)'
                        .'|([^/]++)(*:518)'
                    .')'
                    .'|a(?'
                        .'|sync/editManualOrder/(\\d+)(*:557)'
                        .'|uth/manual/print/(\\d+)(*:587)'
                        .'|ction/(?'
                            .'|edit/([^/]++)(*:617)'
                            .'|remove/([^/]++)(*:640)'
                        .')'
                    .')'
                .')'
                .'|/api/re(?'
                    .'|gister/add/([^/]++)(*:680)'
                    .'|port/([^/]++)/(?'
                        .'|create(*:711)'
                        .'|([^/]++)(*:727)'
                    .')'
                .')'
                .'|/tr(?'
                    .'|uck/(?'
                        .'|accoutability(?:/(\\d+))?(*:774)'
                        .'|edit/(\\d+)(*:792)'
                        .'|create/report/(?'
                            .'|model/show/(\\d+)(*:833)'
                            .'|([^/]++)/(?'
                                .'|show/(\\d+)(*:863)'
                                .'|pdf/(\\d+)(*:880)'
                            .')'
                            .'|export/allreports/(\\d+)(*:912)'
                        .')'
                    .')'
                    .'|avel/accountability/(?'
                        .'|edit/(\\d+)(*:955)'
                        .'|finish(?:/(\\d+))?(*:980)'
                        .'|close/(\\d+)(*:999)'
                        .'|print/(\\d+)(*:1018)'
                    .')'
                .')'
            .')/?$}sD',
    ],
    [ // $dynamicRoutes
        46 => [[['_route' => 'app_boletoreport_boletochangestatus', '_controller' => 'App\\Controller\\BoletoReportController::boletoChangeStatus'], ['boletoId'], ['POST' => 0], null, false, true, null]],
        75 => [[['_route' => 'app_boletoreport_createboletoreport', '_controller' => 'App\\Controller\\BoletoReportController::createBoletoReport'], ['reportName'], ['GET' => 0], null, false, true, null]],
        112 => [[['_route' => 'make_report_print', '_controller' => 'App\\Controller\\ReportController::printAllProductionBalanceReport'], ['entity', 'reportname'], ['POST' => 0], null, false, false, null]],
        126 => [[['_route' => 'app_report_creategenericreportregistry', '_controller' => 'App\\Controller\\ReportController::createGenericReportRegistry'], ['pageType'], ['POST' => 0], null, false, false, null]],
        138 => [[['_route' => 'app_report_getgenericlist', '_controller' => 'App\\Controller\\ReportController::getGenericList'], ['pageType'], ['GET' => 0], null, false, false, null]],
        156 => [[['_route' => 'app_report_vieweditgeneric', '_controller' => 'App\\Controller\\ReportController::viewEditGeneric'], ['entity', 'idConsult'], ['GET' => 0], null, false, true, null]],
        165 => [[['_route' => 'view_report_by_type', '_controller' => 'App\\Controller\\ReportController::openReportPage'], ['reportType'], null, null, false, true, null]],
        197 => [[['_route' => 'app_register_addgenericregister', '_controller' => 'App\\Controller\\RegisterController::addGenericRegister'], ['entity'], ['POST' => 0, 'PUT' => 1], null, false, true, null]],
        220 => [[['_route' => 'app_register_getgenericregister', '_controller' => 'App\\Controller\\RegisterController::getGenericRegister'], ['entity'], ['POST' => 0, 'PATCH' => 1], null, false, true, null]],
        245 => [[['_route' => 'app_register_getregisterwithsimplecriteria', '_controller' => 'App\\Controller\\RegisterController::getRegisterWithSimpleCriteria'], ['entity'], ['POST' => 0], null, false, true, null]],
        269 => [[['_route' => 'app_register_getgenericremover', '_controller' => 'App\\Controller\\RegisterController::getGenericRemover'], ['entity'], ['DELETE' => 0], null, false, true, null]],
        311 => [[['_route' => 'edit_romaneio', '_controller' => 'App\\Controller\\FormController::renderRomaneioFormWithData'], ['romaneioId'], ['GET' => 0], null, false, true, null]],
        330 => [[['_route' => 'app_form_findformtemplate', '_controller' => 'App\\Controller\\FormController::findFormTemplate'], ['formName'], ['GET' => 0], null, false, true, null]],
        350 => [[['_route' => 'overlord', '_controller' => 'App\\Controller\\FormController::printForm'], ['formName'], ['GET' => 0, 'POST' => 1], null, false, false, null]],
        362 => [[['_route' => 'app_form_sendpdfform', '_controller' => 'App\\Controller\\FormController::sendPdfForm'], ['formName'], ['POST' => 0], null, false, false, null]],
        381 => [[['_route' => 'app_form_edittravelaccountability', '_controller' => 'App\\Controller\\FormController::editTravelAccountability'], ['formName', 'idReport'], ['POST' => 0], null, false, false, null]],
        404 => [[['_route' => 'app_form_terminatetravel', '_controller' => 'App\\Controller\\FormController::terminateTravel'], ['formName', 'idOrder'], ['POST' => 0], null, false, false, null]],
        413 => [[['_route' => 'app_form_saveformondb', '_controller' => 'App\\Controller\\FormController::saveFormOnDb'], ['formName'], ['POST' => 0], null, false, true, null]],
        448 => [[['_route' => 'app_form_rxo', '_controller' => 'App\\Controller\\FormController::rxo'], ['formName', 'repoId'], null, null, false, true, null]],
        482 => [[['_route' => 'app_manualorder_closemanualorder', '_controller' => 'App\\Controller\\ManualOrderController::closeManualOrder'], ['orderId'], ['PUT' => 0], null, false, true, null]],
        502 => [[['_route' => 'change_order_status', '_controller' => 'App\\Controller\\ManualOrderController::changeOrderStatus'], ['orderId'], ['POST' => 0], null, false, true, null]],
        518 => [[['_route' => 'no_async_manual_order', '_controller' => 'App\\Controller\\ManualOrderController::editManualOrderAsync'], ['orderModelId'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        557 => [[['_route' => 'async_edit_manual_order', '_controller' => 'App\\Controller\\ManualOrderController::editManualOrderAsync'], ['orderModelId'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        587 => [[['_route' => 'app_manualorder_printmanualorderproductauthallowwithdraw', '_controller' => 'App\\Controller\\ManualOrderController::printManualOrderProductAuthAllowWithdraw'], ['orderId'], null, null, false, true, null]],
        617 => [[['_route' => 'edit.order', '_controller' => 'App\\Controller\\OrderController::redirectOrderActions'], ['id'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        640 => [[['_route' => 'app_order_removeorder', '_controller' => 'App\\Controller\\OrderController::removeOrder'], ['id'], ['DELETE' => 0], null, false, true, null]],
        680 => [[['_route' => 'app_register_addgenericregisterajax', '_controller' => 'App\\Controller\\RegisterController::addGenericRegisterAjax'], ['entity'], ['POST' => 0, 'PUT' => 1], null, false, true, null]],
        711 => [[['_route' => 'app_report_creategenericreportregistryajax', '_controller' => 'App\\Controller\\ReportController::createGenericReportRegistryAjax'], ['pageType'], ['POST' => 0], null, false, false, null]],
        727 => [[['_route' => 'app_report_getsingledatagenericajax', '_controller' => 'App\\Controller\\ReportController::getSingleDataGenericAjax'], ['entity', 'consultId'], null, null, false, true, null]],
        774 => [[['_route' => 'truck_accountability_index', 'accountabilityReportId' => 0, '_controller' => 'App\\Controller\\TravelAccountabilityController::createtruckArrivalAccountabilityReport'], ['accountabilityReportId'], ['POST' => 0, 'GET' => 1], null, false, true, null]],
        792 => [[['_route' => 'app_truck_edittruckorderreport', '_controller' => 'App\\Controller\\TruckController::editTruckOrderReport'], ['id'], ['GET' => 0, 'POST' => 1], null, false, true, null]],
        833 => [[['_route' => 'app_truck_createmodelsnamesreport', '_controller' => 'App\\Controller\\TruckController::createModelsNamesReport'], ['truckReportId'], null, null, false, true, null]],
        863 => [[['_route' => 'app_truck_createsinglereports', '_controller' => 'App\\Controller\\TruckController::createSingleReports'], ['typeReport', 'entityId'], null, null, false, true, null]],
        880 => [[['_route' => 'app_truck_createpdfreport', '_controller' => 'App\\Controller\\TruckController::createPdfReport'], ['typeReport', 'entityId'], null, null, false, true, null]],
        912 => [[['_route' => 'app_truck_getallreportsinzipfile', '_controller' => 'App\\Controller\\TruckController::getAllReportsInZipFile'], ['entityId'], null, null, false, true, null]],
        955 => [[['_route' => 'truck_edit', '_controller' => 'App\\Controller\\TravelAccountabilityController::editAccountabilityReport'], ['accountabilityReportId'], ['POST' => 0, 'GET' => 1], null, false, true, null]],
        980 => [[['_route' => 'finish_accountability_report', 'accountabilityRepoId' => 0, '_controller' => 'App\\Controller\\TravelAccountabilityController::finishAccountabilityReport'], ['accountabilityRepoId'], ['POST' => 0], null, false, true, null]],
        999 => [[['_route' => 'app_travelaccountability_closeaccountabilityorderreport', '_controller' => 'App\\Controller\\TravelAccountabilityController::closeAccountabilityOrderReport'], ['accountabilityReportId'], ['POST' => 0], null, false, true, null]],
        1018 => [
            [['_route' => 'print_accountability_report', '_controller' => 'App\\Controller\\TravelAccountabilityController::printTravelAccountabilityReport'], ['accountabilityRepoId'], ['GET' => 0, 'POST' => 1], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
