<?php

namespace App\EventSubscriber;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

class CsrfAuthSubscriber extends Controller implements EventSubscriberInterface
{
    protected static $valid= true;

    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        if ($request->server->get('REQUEST_METHOD') == 'POST' && $request->server->get('APP_ENV') == 'dev') {
            $token = $request->request->get('token');
            $controller = $event->getController()[0];
            if (!$controller->isCsrfTokenValid('token-v', $token)) {
                self::$valid = false;
                //die('token incorreto, por favor volte a pagina anterior, não recarrege a pagina');
            }
        }
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        if (!self::$valid) {
            return $event->setResponse(new Response('<b>É tetra</b>'));
        }
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.controller'  => 'onKernelController',
           'kernel.response'    => 'onKernelResponse'
        ];
    }
}
