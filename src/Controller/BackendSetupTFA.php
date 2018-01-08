<?php

namespace DieSchittigs\TwoFactorAuth\Controller;

use DieSchittigs\TwoFactorAuth\TwoFactorFactory;
use DieSchittigs\TwoFactorAuth\Template\BackendTwoFactorTemplate;
use Contao\Backend;

class BackendSetupTFA extends Backend
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->import('BackendUser', 'user');
        parent::__construct();

        $this->template = new BackendTwoFactorTemplate('be_2fa_setupform');
        $this->auth = TwoFactorFactory::generate();
        $this->secret = $this->auth->createSecret();
    }

    /**
     * Handles the input of the setup form and sets the user's new 2FA secret
     */
    protected function handleInput()
    {
        if (\Input::post('tfa_secret')) {
            $this->secret = \Input::post('tfa_secret');
        }

        if (\Input::post('FORM_SUBMIT') == 'tl_2fa_setup') {
            if (TwoFactorFactory::verifyCode($this->secret, \Input::post('tfa_code'))) {
                // Save the user's new secret.
                $this->user->tfaSecret = $this->secret;
                $this->user->tfaChange = '';
                $this->user->save();

                // Redirect back to backend index
                $this->redirect('/contao/main.php');
            } else {
                \Message::addError($GLOBALS['TL_LANG']['tl_user']['tfa_exception_invalid']);
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

        $this->handleInput();

        $this->template->imageUrl = $this->auth->getQrCodeImageAsDataUri($this->user->email, $this->secret, 200);
        $this->template->secret   = $this->secret;

        \Message::addInfo($GLOBALS['TL_LANG']['tl_user']['tfa_explanation']);

        return $this->template->getResponse();
    }
}