<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

class ExceptionSubscriber implements EventSubscriberInterface
{
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exceptionClass = get_class($event->getException());
        $exception = $exceptionClass === 'Exception' ? $exceptionClass : substr_replace($exceptionClass, '', 0, (strrpos($exceptionClass, '\\')+1));
        switch ($exception) {
            case 'AccessDeniedException':
                //dump($event->getRequest()->headers->get('referer'));
                $this->triggerFlashMessage($event, $event->getException()->getMessage(), 'error');
                //return $event->setResponse(new Response($event->getRequest()->headers->get('referer'), 301));
                return $event->setResponse(new ResponseResponse($event->getRequest()->headers->get('referer')));
                break;
            case 'UniqueConstraintViolationException':
            case 'ForeignKeyConstraintViolationException':
                $this->triggerFlashMessage($event, 'Item ja está cadastrado em algum registro e não pode ser removido. COD::400', 'error');
                return $event->setResponse(new RedirectResponse($event->getRequest()->headers->get('referer'), 301));
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

    /**
     * Lança mensagens que serão disparadas em flash notification
     *
     * @param  GetResponseForExceptionEvent $event   Objeto de evento
     * @param  string                       $message Mensagem que será disparada
     * @param  string                       $type    Tipo de mensagen que será disparada
     * @return void
     */
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
