<?php

namespace Contao;

class TwoFactorFieldListener extends \Backend
{
    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->import('BackendUser', 'user');
        $this->import('Database', 'database');

        parent::__construct();
    }

    /**
     * Callback that modifies the secret before saving
     * 
	 * @param string $value The secret that is trying to be saved
	 * @return string The secret that will be persisted to the database
     */
    public function saveSecret($value)
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

    /**
     * Resets the user's 2FA secret if the reset checkbox was checked
     * 
	 * @param mixed $value The value of the checkbox
	 * @param DataContainer $value The data container used to save
	 * @return null
     */
    public function saveForceChangeField($value, DataContainer $dc)
    {
        if ($value) {
            $this->database->prepare("UPDATE tl_user SET tfaSecret='' WHERE id=?")
                ->execute($dc->id);
        }

        return null;
    }
}