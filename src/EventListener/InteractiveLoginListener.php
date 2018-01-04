<?php

namespace DieSchittigs\TwoFactorAuth\EventListener;

use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Contao\User;

class InteractiveLoginListener
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    /**
     * @var SessionInterface
     */
    private $session;

    public function __construct(ContaoFrameworkInterface $framework, SessionInterface $session)
    {
        $this->framework = $framework;
        $this->session = $session;
    }

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
        
        $this->session->set('2fa_required', true);
    }
}