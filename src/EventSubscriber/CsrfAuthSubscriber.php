<?php

namespace App\EventSubscriber;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class CsrfAuthSubscriber extends Controller implements EventSubscriberInterface
{
    public function onKernelController(FilterControllerEvent $event)
    {
        $request = $event->getRequest();
        if ($request->server->get('REQUEST_METHOD') == 'POST' && $request->server->get('APP_ENV') == 'prod') {
            $token = $request->request->get('token');
            if (!$event->getController()[0]->isCsrfTokenValid('valid-token', $token)) {
                throw new AccessDeniedException('Token invalido');
            }
            return;
        }
        return;
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.controller' => 'onKernelController',
        ];
    }
}
