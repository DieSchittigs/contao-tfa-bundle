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

        // Skip the validation if the user has 2FA enabled and doesn't want to deactivate it.
        if ($this->user->tfaSecret && !$this->Input->post('deactivate_tfa')) {
            return;
        }

        // Skip the validation if the user doesn't want to activate 2FA.
        if (!$this->user->tfaSecret && !$code) {
            return;
        }

        // Obtain the secret from the user so we don't have to display it anymore once set.
        if (!$secret) {
            $secret = $this->user->tfaSecret;
        }

        // Verify the entered code with the secret.
        $auth = new TwoFactorAuth;
        if (!$auth->verifyCode($secret, $code)) {
            $this->addError($GLOBALS['TL_LANG']['tl_user']['tfa_exception_invalid']);
        }

        return parent::validator($secret);
    }

    public function parse()
    {
        $title = $GLOBALS['TL_CONFIG']['websiteTitle'];
        $auth = new TwoFactorAuth($title);

        if ($this->user->tfaSecret) {
            // Prefer the user's saved secret.
            $this->secret = $this->user->tfaSecret;
        } elseif ($this->value) {
            // If a value is set (from a failed validation), prefer this value.
            $this->secret = $this->value;
        } else {
            // Otherwise generate a new secret
            $this->secret = $auth->createSecret();
        }

        $this->imageUrl = $auth->getQrCodeImageAsDataUri($this->user->email, $this->secret, 200);
        $this->tfaEnabled = (bool) $this->user->tfaSecret;

        return parent::parse();
    }

    public function generate()
    {
        return $this->parse();
    }
}
