<?php

namespace App\EventSubscriber;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class CsrfAuthSubscriber extends Controller implements EventSubscriberInterface
{
    /**
     * @param FilterControllerEvent $event
     */
    public function onKernelController(FilterControllerEvent $event): void
    {
        $request = $event->getRequest();
        if ($request->getMethod() == 'POST') {
            $session = $request->getSession();
            $tokenId = $session->get('csrftoken')->getId();
            $tokenValue = $request->request->get('token');
            dump($request->headers->get('auth'));
            if (is_null($tokenValue)) {
                $tokenValue = $request->headers->get('auth');
            }
            $controller = $event->getController()[0];
            if (!$controller->isCsrfTokenValid($tokenId, $tokenValue)) {
                throw new AccessDeniedException("Token invalido.");
            }
            foreach ($session->all() as $key => $value) {
                $session->remove($key);
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
