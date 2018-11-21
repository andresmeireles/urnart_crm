<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

class TokenSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $session = $event->getRequest()->getSession();
        if(!array_key_exists('csrftoken', $session->all())) {
            $ctm = new CsrfTokenManager;
            $token = $ctm->getToken((new \DateTime('now'))->format('d-m-Y H:m:s'));
            $session->set('csrftoken', $token);
        }
        if (empty(trim($session->get('csrftoken')))) {
            $ctm = new CsrfTokenManager;
            $token = $ctm->getToken((new \DateTime('now'))->format('d-m-Y H:m:s'));
            $session->set('csrftoken', $token);
        }
        return;
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.request' => 'onKernelRequest',
        ];
    }
}
