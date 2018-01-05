<?php

namespace DieSchittigs\TwoFactorAuth\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Contao\TwoFactorAuthTemplate;
use RobThree\Auth\TwoFactorAuth;

class RequestListener
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
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    public function __construct(ContaoFrameworkInterface $framework, SessionInterface $session, TokenStorageInterface $tokenStorage)
    {
        $this->framework = $framework;
        $this->session = $session;
        $this->tokenStorage = $tokenStorage;
    }

    private function initializeFramework()
    {
        $this->framework->initialize();

        \System::loadLanguageFile('default');
        \System::loadLanguageFile('tl_user');
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        $this->initializeFramework();

        // If the session isn't running, or a 2FA token isn't required, return.
        if (!$this->session->isStarted() || !$this->session->get('2fa_required') || TL_MODE == 'FE') {
            return;
        }

        $template = new TwoFactorAuthTemplate('be_2fa_loginform');

        if ($_POST['FORM_SUBMIT'] == 'tl_login') {
            $secret = $this->tokenStorage->getToken()->getUser()->tfaSecret;
            $code = \Input::post('2fa_code');
            
            $auth = new TwoFactorAuth;
            if ($auth->verifyCode($secret, $code)) {
                $this->session->set('2fa_required', false);
                return;
            } else {
                $template->incorrect = $GLOBALS['TL_LANG']['tl_user']['tfa_exception_invalid'];
            }
        }
             
        $event->setResponse(Response::create($template->parse()));
    }
}