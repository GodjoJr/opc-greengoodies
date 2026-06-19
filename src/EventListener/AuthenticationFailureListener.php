<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\JsonResponse;

#[AsEventListener(event: 'lexik_jwt_authentication.on_authentication_failure', method: 'onAuthenticationFailure')]
class AuthenticationFailureListener
{
    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $exception = $event->getException()->getPrevious() 
            ?? $event->getException();

        if ($exception->getMessage() === 'Accès API non activé') {
            $event->setResponse(new JsonResponse(
                ['message' => 'Accès API non activé'],
                403
            ));
            return;
        }

        $event->setResponse(new JsonResponse(
            ['message' => 'Identifiants incorrects'],
            401
        ));
    }
}