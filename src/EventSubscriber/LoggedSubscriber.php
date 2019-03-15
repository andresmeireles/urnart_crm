<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class LoggedSubscriber implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // ...
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.exception' => 'onKernelException',
        ];
    }
}
