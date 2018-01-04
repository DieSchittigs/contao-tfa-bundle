<?php

namespace DieSchittigs\TwoFactorAuth\EventListener;

use Contao\CoreBundle\Framework\ContaoFrameworkInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;
use Contao\User;
use Contao\BackendTemplate;
use Contao\TwoFactorAuthentication;

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

    // TokenStorage
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

        $template = new BackendTemplate('be_2fa_loginform');

        if ($_POST['FORM_SUBMIT'] == 'tl_login') {
            $secret = $this->tokenStorage->getToken()->getUser()->tfaSecret;
            $code = \Input::post('2fa_code');
            
            if (TwoFactorAuthentication::verifyCode($secret, $code)) {
                $this->session->set('2fa_required', false);
                return;
            } else {
                $template->incorrect = $GLOBALS['TL_LANG']['tl_user']['tfa_exception_invalid'];
            }
        }
        
		$template->theme = \Backend::getTheme();
		$template->base = \Environment::get('base');
		$template->language = $GLOBALS['TL_LANGUAGE'];
		$template->messages = \Message::generate();
		$template->languages = \System::getLanguages(true); // backwards compatibility
		$template->charset = \Config::get('characterSet');
		$template->action = ampersand(\Environment::get('request'));
		$template->userLanguage = $GLOBALS['TL_LANG']['tl_user']['language'][0];
		$template->curLanguage = \Input::post('language') ?: str_replace('-', '_', $GLOBALS['TL_LANGUAGE']);
		$template->curUsername = \Input::post('username') ?: '';
		$template->loginButton = \StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['continue']);
        $template->jsDisabled = $GLOBALS['TL_LANG']['MSC']['jsDisabled'];
        
		$template->title = \StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['loginTo'], \Config::get('websiteTitle')));
        $template->headline = $GLOBALS['TL_LANG']['tl_user']['tfa_title'];
        $template->feLink = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $template->f2a_code = $GLOBALS['TL_LANG']['MSC']['tfaCode'];
     
        $event->setResponse(Response::create($template->parse()));
    }
}