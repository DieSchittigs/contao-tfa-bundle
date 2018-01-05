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

    /**
     * Initializes the listener
     * 
	 * @param ContaoFrameworkInterface $framework A framework instance
	 * @param SessionInterface $session The current session object
     */
    public function __construct(ContaoFrameworkInterface $framework, SessionInterface $session)
    {
        $this->framework = $framework;
        $this->session = $session;
    }

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
        
        $this->session->set('2fa_required', true);
    }
}