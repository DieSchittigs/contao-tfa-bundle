<?php

namespace DieSchittigs\TwoFactorAuth\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Contao\User;

class InteractiveLoginListener
{
    /**
     * Sets a session variable for users that have 2FA enabled after login
     * 
     * @param InteractiveLoginEvent $event The event this listener responds to
     */
    public function onInteractiveLogin(InteractiveLoginEvent $event)
    {
        $user = $event->getAuthenticationToken()->getUser();

        // Return if ...
        // - we couldn't retrieve a user
        // - the user doesn't have 2FA enabled
        // - a frontend member tries to login (we currently only support Backend users)
        if (!$user instanceof User || !$user->tfaSecret || TL_MODE == 'FE') {
            return;
        }

        $session = \Session::getInstance();
        $session->set('2fa_required', true);
    }
}