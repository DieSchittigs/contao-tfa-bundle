<?php

namespace Contao;

class TwoFactorSaveListener extends \Backend
{
    public function __construct()
    {
        $this->import('BackendUser', 'user');

        parent::__construct();
    }

    public function save($value)
    {
        // Return an empty string if the user wishes to deactivate 2FA.
        if ($this->Input->post('deactivate_tfa')) {
            return '';
        }

        // Make sure to return the user's secret if it isn't present in the response.
        if (!$value && $this->user->tfaSecret) {
            return $this->user->tfaSecret;
        }

        return $value;
    }
}