<?php declare(strict_types = 1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

final class ExceptionSubscriber implements EventSubscriberInterface
{
    /**
     * Evento que define ações para exceções do php
     *
     * @param   GetResponseForExceptionEvent $event
     * @return  Response|ExceptionSubscriber
     */
    public function onKernelException(GetResponseForExceptionEvent $event)
    {
//        if (getEnv('APP_ENV') === 'dev') {
//            return $this;
//        }
        $exceptionClass = get_class($event->getException());
        $exception = $exceptionClass === 'Exception' ?
                        $exceptionClass :
                        substr_replace(
                            $exceptionClass,
                            '',
                            0,
                            strrpos($exceptionClass, '\\') + 1
                        );
        if ($event->getRequest()->server->get('APP_ENV') === 'dev') {
            $exception = $exceptionClass === 'Exception' ||
            $exceptionClass === 'Twig_Error_Loader' ||
            $exceptionClass === 'JsonException' ?
            $exceptionClass :
            substr_replace(
                $exceptionClass,
                '',
                0,
                strrpos($exceptionClass, '\\') + 1
            );
        }
        $refererLink = $event->getRequest()->headers->get('referer');
        if (! is_string($refererLink)) {
            $refererLink = '/';
        }

        switch ($exception) {
            case 'AccessDeniedException':
            case 'AccessDeniedHttpException':
            case 'CustomException':
            case 'CustomUserMessageAuthenticationException':
            case 'FieldAlreadExistsException':
            case 'InvalidCsrfTokenException':
                $this->triggerFlashMessage(
                    $event,
                    $event->getException()->getMessage(),
                    'error'
                );
                return $event->setResponse(new RedirectResponse($refererLink, 301, []));
                break;
            case 'UniqueConstraintViolationException':
            case 'ForeignKeyConstraintViolationException':
                $this->triggerFlashMessage(
                    $event,
                    'Item ja está cadastrado em algum registro e não pode ser removido. COD::400',
                    'error'
                );
                return $event->setResponse(new RedirectResponse($refererLink));
                break;
            case 'JsonException':
                exit($event->getException()->getMessage());
                break;
            case 'AuthenticationException':
                return $event->setResponse(new Response(
                    $event->getException()->getMessage(),
                    Response::HTTP_EXPECTATION_FAILED,
                    ['ninja']
                ));
                break;
            case 'Twig_Error_Loader':
                return new Response('Deu ruim meu amigo.');
                break;
            case 'BadRefererLinkException':
            default:
                return $this;
                break;
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.exception' => 'onKernelException',
        ];
    }

    /**
     * Lança mensagens que serão disparadas em flash notification
     *
     * @param  GetResponseForExceptionEvent $event
     * @param  string                       $message
     * @param  string                       $type
     */
    private function triggerFlashMessage(
        GetResponseForExceptionEvent $event,
        string $message,
        string $type
    ): void {
        $event->getRequest()->getSession()->getFlashBag()->add(
            $type,
            $message
        );
    }
}
