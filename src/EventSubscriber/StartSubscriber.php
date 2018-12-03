<?php

namespace App\EventSubscriber;

use App\Config\Config;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Csrf\CsrfTokenManager;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class StartSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $session = $event->getRequest()->getSession();
        if (!Config::getStatus()) {
            Config::start();
        }
        if (!array_key_exists('csrftoken', $session->all())) {
            $this->setTokens($session);
        }
        if (empty(trim($session->get('csrftoken')))) {
            $this->setTokens($session);
        }
        return;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $session = $event->getRequest()->getSession();
        if (!array_key_exists('csrftoken', $session->all())) {
            $this->setTokens($session);
        }
        if (empty(trim($session->get('csrftoken')))) {
            $this->setTokens($session);
        }
        return;
    }
    
    public function setTokens($session): void
    {
        $ctm = new CsrfTokenManager;
        $token = $ctm->getToken((new \DateTime('now'))->format('d-m-Y H:m:s'));
        $session->set('csrftoken', $token->getId());
    }
    
    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
            'kernel.controller' => 'onKernelController'
        ];
    }
}
