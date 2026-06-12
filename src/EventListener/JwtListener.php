<?php

namespace App\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTAuthenticatedEvent;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

#[AsEventListener(event: 'lexik_jwt_authentication.on_jwt_authenticated')]
class JwtListener
{
    public function __invoke(JWTAuthenticatedEvent $event): void
    {
        $user = $event->getToken()->getUser();

        if (!$user->isApiAccess()) {
            throw new AccessDeniedHttpException('Accès API non activé.');
        }
    }
}