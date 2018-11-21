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
            $tokenString = $session->get('csrftoken');
            $token = $request->request->get('token');
            if (is_null($token)) {
                $token = $request->headers->get('auth');
            }
            $controller = $event->getController()[0];
            if (!$controller->isCsrfTokenValid($tokenString, $token)) {
                throw new AccessDeniedException("Token invalido.");
            }
            $ctm = new CsrfTokenManager;
            $newTokenString = $ctm->getToken((new \DateTime('now'))->format('d-m-Y H:m:s'));
            $session->set('csrftoken', $newTokenString);
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
