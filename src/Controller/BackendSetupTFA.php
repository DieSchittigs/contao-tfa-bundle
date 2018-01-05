<?php

namespace DieSchittigs\TwoFactorAuth\Controller;

use DieSchittigs\TwoFactorAuth\TwoFactorFactory;
use DieSchittigs\TwoFactorAuth\Template\BackendTwoFactorTemplate;
use Contao\Backend;

class BackendSetupTFA extends Backend
{
    public function __construct()
    {
        $this->import('BackendUser', 'user');
        parent::__construct();

        $this->template = new BackendTwoFactorTemplate('be_2fa_setupform');
        $this->auth = TwoFactorFactory::generate();
        $this->secret = $this->auth->createSecret();
    }

    protected function checkInput()
    {
        if (\Input::post('tfa_secret')) {
            $this->secret = \Input::post('tfa_secret');
        }

        if (\Input::post('FORM_SUBMIT') == 'tl_2fa_setup') {
            if (TwoFactorFactory::verifyCode($this->secret, \Input::post('tfa_code'))) {
                // Save secret for user.

                $this->user->tfaSecret = $this->secret;
                $this->user->save();

                $this->redirect('/contao/main.php');
            } else {
                $this->template->incorrect = $GLOBALS['TL_LANG']['tl_user']['tfa_exception_invalid'];
            }
        }
    }

	/**
	 * Run the controller and parse the two-factor template
	 *
	 * @return Symfony\Component\HttpFoundation\Response
	 */
    public function run()
    {
        if (!TwoFactorFactory::tfaSetupRequired($this->user)) {
            $this->redirect('/contao/main.php');
        }

        $this->template->imageUrl = $this->auth->getQrCodeImageAsDataUri($this->user->email, $this->secret, 200);
        $this->template->secret = $this->secret;

        return $this->template->getResponse();
    }
}