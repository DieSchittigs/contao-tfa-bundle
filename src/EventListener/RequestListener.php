<?php

namespace DieSchittigs\TwoFactorAuth\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use DieSchittigs\TwoFactorAuth\TwoFactorFactory;
use Symfony\Component\HttpFoundation\Response;
use Contao\TwoFactorAuthTemplate;
use Contao\Controller;
use Contao\Session;
use Contao\BackendUser;

class RequestListener
{
    /**
     * @var ContaoFrameworkInterface
     */
    private $framework;

    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * Initializes the listener
     * 
     * @param ContaoFrameworkInterface $framework A framework instance
     * @param TokenStorageInterface $tokenStorage The current token storage
     */
    public function __construct(ContaoFrameworkInterface $framework, TokenStorageInterface $tokenStorage)
    {
        $this->framework = $framework;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Displays a 2FA template when a user still needs to authenticate himself
     * 
     * @param GetResponseEvent $event The event the listener responds to
     */
    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->framework->initialize();
        
        // Don't run if we are in Frontend mode.
        if (TL_MODE == 'FE') {
            return;
        }

        $session = Session::getInstance();

        // Force a user to enter his code
        if ($session->get('2fa_required') && $event->getRequest()->get('_route') != 'contao_backend_enter_tfa_code') {
            Controller::redirect('/contao/tfa');
        }

        // Force a user to set up 2FA
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return;
        }
        
        $user = $token->getUser();
        if ($user instanceof BackendUser && TwoFactorFactory::tfaSetupRequired($user) && $event->getRequest()->get('_route') != 'contao_backend_set_tfa') {
            Controller::redirect('/contao/tfa/set');
        }
    }
}