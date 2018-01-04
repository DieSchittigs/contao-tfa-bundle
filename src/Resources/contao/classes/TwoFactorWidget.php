<?php

namespace Contao;

use RobThree\Auth\TwoFactorAuth;

class TwoFactorWidget extends \Widget
{
	/**
	 * Submit user input
	 * @var boolean
	 */
    protected $blnSubmitInput = true;
    
    protected $strTemplate = 'be_2fa_field';

    public function __construct($arrAttributes = null)
    {
        $this->import('BackendUser', 'user');

        parent::__construct($arrAttributes);
    }

    protected function validator($secret)
    {
        $code = $this->Input->post('tfaToken');

        if (!TwoFactorAuthentication::verifyCode($secret, $code)) {
            $this->addError($GLOBALS['TL_LANG']['tl_user']['tfa_exception_invalid']);
        }

        return parent::validator($secret);
    }

    public function parse()
    {
        $auth = TwoFactorAuthentication::generateAuthenticator();

        // Only create a new secret, if it isn't already set.
        $this->secret = $this->value ? $this->value : $auth->createSecret();
        $this->imageUrl = $auth->getQrCodeImageAsDataUri($this->user->email, $this->secret, 200);
        $this->tfaEnabled = (bool) $this->value;

        return parent::parse();
    }

    public function generate()
    {
        return $this->parse();
    }
}