<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use App\Utils\Exceptions\BadRefererLinkException;

class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * Evento que define ações para exceções do php
     *
     * @param   GetResponseForExceptionEvent  $event  Objeto de evento
     *
     * @return  Response|ExceptionSubscriber                                [return description]
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    { 
        $exceptionClass = get_class($event->getException());
        
        $exception = $exceptionClass === 'Exception' ? $exceptionClass : substr_replace($exceptionClass, '', 0, (strrpos($exceptionClass, '\\')+1));
        
        if ($event->getRequest()->server->get('APP_ENV') === 'dev') {
            $exception = $exceptionClass === 'Exception' || $exceptionClass === 'Twig_Error_Loader' ? $exceptionClass : substr_replace($exceptionClass, '', 0, (strrpos($exceptionClass, '\\')+1));
        }

        $refererLink = $event->getRequest()->headers->get('referer');
        if (!is_string($refererLink)) {
            $refererLink = '/';
        }
        switch ($exception) {
            case 'AccessDeniedException':
            case 'FieldAlreadExistsException':
            case 'CustomException':
            case 'CustomUserMessageAuthenticationException';
                $this->triggerFlashMessage($event, $event->getException()->getMessage(), 'error');
                return $event->setResponse(new RedirectResponse($refererLink));
                break;
            case 'UniqueConstraintViolationException':
            case 'ForeignKeyConstraintViolationException':
                $this->triggerFlashMessage($event, 'Item ja está cadastrado em algum registro e não pode ser removido. COD::400', 'error');
                return $event->setResponse(new RedirectResponse($refererLink));
                break;
            case 'Exception':
                return $event->setResponse(new Response(
                    $event->getException()->getMessage(),
                    301,
                    array('type' => 'ninja')
                ));
                break;
            case 'Twig_Error_Loader':
                //return new Response('Página não existe. Caminho incorreto.');
                return new Response('Deu ruim meu amigo.');
                break;
            case 'BadRefererLinkException':
            default:
                return $this;
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
