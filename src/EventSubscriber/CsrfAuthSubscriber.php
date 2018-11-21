<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class CsrfAuthSubscriber extends Controller implements EventSubscriberInterface
{
    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event): void
    {
        $request = $event->getRequest();
        $session = $request->getSession();
        if ($request->getMethod() == 'POST') {
            $token = $request->request->get('token');
            if (is_null($token)) {
                $token = $request->headers->get('auth');
            }
            $controller = $event->getController()[0];
            if (!$controller->isCsrfTokenValid('token', $token)) {
                throw new AccessDeniedException("Token invalido.");
            }
            return;
        }
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.controller'  => 'onKernelController',
        ];
    }
}
