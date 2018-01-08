<?php

namespace Contao;

use DieSchittigs\TwoFactorAuth\TwoFactorFactory;

class TwoFactorSecretWidget extends \Widget
{
    /**
     * {@inheritdoc}
     */
    protected $blnSubmitInput = true;
    
    /**
     * {@inheritdoc}
     */
    protected $strTemplate = 'be_2fa_field';

    /**
     * {@inheritdoc}
     */
    public function __construct($attributes = null)
    {
        $this->import('BackendUser', 'user');

        parent::__construct($attributes);
    }

	/**
	 * Validate the code the user has entered based on the secret
	 *
	 * @param mixed $secret The user input
	 * @return mixed The original or modified user input
	 */
    protected function validator($secret)
    {
        $code = $this->Input->post('tfaToken');
        $this->checked = $this->Input->post('deactivate_tfa');

        // Skip the validation if the user hasn't entered a code.
        if (!$code) { 
            return;
        }

        // Obtain the secret from the user so we don't have to display it anymore once set.
        if (!$secret) {
            $secret = $this->user->tfaSecret;
        }

        // Verify the entered code with the secret.
        if (!TwoFactorFactory::verifyCode($secret, $code)) {
            $this->addError($GLOBALS['TL_LANG']['tl_user']['tfa_exception_invalid']);
        }

        return parent::validator($secret);
    }

    /**
     * {@inheritdoc}
     */
    public function parse($attributes = null)
    {
        $auth = TwoFactorFactory::generate();

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
        $this->tfaEnabled = strlen($this->user->tfaSecret) > 0;
        $this->helptext = $GLOBALS['TL_LANG']['tl_user']['tfa_help_input'];

        return parent::parse($attributes);
    }

    /**
     * {@inheritdoc}
     */
    public function generate()
    {
        return $this->parse();
    }
}
