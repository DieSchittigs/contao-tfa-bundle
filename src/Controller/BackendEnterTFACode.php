<?php

namespace DieSchittigs\TwoFactorAuth\Controller;

use DieSchittigs\TwoFactorAuth\TwoFactorFactory;
use DieSchittigs\TwoFactorAuth\Template\BackendTwoFactorTemplate;

class BackendEnterTFACode extends \Backend
{
    public function __construct()
    {
        $this->import('BackendUser', 'user');
        parent::__construct();

        \System::loadLanguageFile('default');
        \System::loadLanguageFile('tl_user');
    }

	/**
	 * Run the controller and parse the two-factor template
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 */
    public function run()
    {
        $session = \Session::getInstance();
        $template = new BackendTwoFactorTemplate('be_2fa_loginform');
        
        if (!$session->get('2fa_required')) {
            $this->redirect('contao/main.php');
        }

        if (\Input::post('FORM_SUBMIT') == 'tl_2fa_code') {
            $secret = $this->user->tfaSecret;
            $code = \Input::post('tfa_code');
            
            if (TwoFactorFactory::verifyCode($secret, $code)) {
                $session->set('2fa_required', false);

                $this->redirect('contao/main.php');
            } else {
                $template->incorrect = $GLOBALS['TL_LANG']['tl_user']['tfa_exception_invalid'];
            }
        }

        return $template->getResponse();
    }
}