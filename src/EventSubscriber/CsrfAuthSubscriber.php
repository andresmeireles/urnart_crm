<?php

namespace App\EventSubscriber;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
//use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotAcceptableHttpException;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use App\Utils\Andresmei\CsrfToken;

class CsrfAuthSubscriber extends AbstractController implements EventSubscriberInterface
{
    /**
     * @param FilterControllerEvent $event
     */
    //public function onKernelController(FilterControllerEvent $event): self
    public function onKernelRequest(GetResponseEvent $event): self
    {
        $request = $event->getRequest();
        if ($request->getMethod() == 'POST') {
            $session = $request->getSession();
            $tokenId = $session->get('csrfToken')->getCsrfTokenString();
            $tokenValue = $request->request->get('token');
            if (is_null($tokenValue)) {
                $tokenValue = $request->headers->get('auth');
            }
            if ( !(new CsrfToken())->isValid($tokenId, $tokenValue) ) {
                //throw new AccessDeniedException("Token invalido.");
                throw new NotAcceptableHttpException(sprintf('Token %s não aceito, token correto é %s', 
                $tokenValue,
                $session->get('csrfToken')->getCsrfToken()));
            }
            foreach ($session->all() as $key => $value) {
                $session->remove($key);
            }
        }
        return $this;
    }

    public static function getSubscribedEvents()
    {
        return [
           //'kernel.controller'  => 'onKernelController',
           'kernel.request' => ['onKernelRequest', 8] // alta prioridade para ver o checkar no login
        ];
    }
}
