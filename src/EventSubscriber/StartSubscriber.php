<?php

namespace App\EventSubscriber;

use App\Config\Config;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use App\Utils\Andresmei\CsrfToken;

class StartSubscriber implements EventSubscriberInterface
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $session = $event->getRequest()->getSession();
        if (!Config::getStatus()) {
            Config::start();
        }
        if (!array_key_exists('csrfToken', $session->all())) {
            $csrfToken = $this->setTokens();
            $session->set('csrfToken', $csrfToken);
        }
        return;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $session = $event->getRequest()->getSession();
        if (!array_key_exists('csrfToken', $session->all())) {
            $csrfToken = $this->setTokens();
            $session->set('csrfToken', $csrfToken);
        }
        return;
    }
    
    private function setTokens(): CsrfToken
    {
        $string = (new \DateTime('now'))->format('d/m/Y H:m:s');
        return new CsrfToken($string);
    }
    
    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
            'kernel.controller' => 'onKernelController'
        ];
    }
}
