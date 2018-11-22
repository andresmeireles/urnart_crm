<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpFoundation\Response;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exceptionClass = get_class($event->getException());
        $exception = $exceptionClass === 'Exception' ? $exceptionClass : substr_replace($exceptionClass, '', 0, (strrpos($exceptionClass, '\\')+1));
        switch ($exception) {
            case 'AccessDeniedException':
                //dump($event->getRequest()->headers->get('referer'));
                //echo $event->getException()->getMessage();
                
                return $event->setResponse(new Response($event->getException()->getMessage(), 301));
                break;
            case 'ForeignKeyConstraintViolationException':
                return $event->setResponse(new Response(
                    'Item ja está cadastrado em algum registro e não pode ser removido. COD::400',
                    301,
                    array('type' => 'ninja')
                ));
                break;
            case 'Exception':
                return $event->setResponse(new Response(
                    'Alguma exceção',
                    301,
                    array('type' => 'ninja')
                ));
                break;
            default:
                return;
                break;
        }
    }

    private function triggerFlashMessage(GetResponseForExceptionEvent $event, string $message, string $type): void
    {
        $event->getRequest()->getSession()->getFlashBag()->add(
            $type,
            $message
        );
    }

    public static function getSubscribedEvents()
    {
        return [
           'kernel.exception' => 'onKernelException',
        ];
    }
}
