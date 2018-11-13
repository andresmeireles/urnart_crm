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
        if ($request->getMethod() == 'POST') {
            $token = $request->request->get('token');
            if (is_null($token)) {
                $token = $request->headers->get('auth');
            }
            $controller = $event->getController()[0];
            if (!$controller->isCsrfTokenValid('token', $token)) {
                throw new AccessDeniedException("Token invalido.");
            }
        }
    }

    /**
     * @param GetResponseForExceptionEvent $event
     *
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();
        if (strpos(get_class($exception), 'AccessDeniedException')) {
            return $event->setResponse(new Response($exception->getMessage(), 301));
        }
    }*/

    public static function getSubscribedEvents()
    {
        return [
           'kernel.controller'  => 'onKernelController',
           //'kernel.exception'   => 'onKernelException'
        ];
    }
}
