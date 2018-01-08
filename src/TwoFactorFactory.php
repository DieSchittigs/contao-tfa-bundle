<?php

namespace DieSchittigs\TwoFactorAuth;

use RobThree\Auth\TwoFactorAuth;
use Contao\BackendUser;


class TwoFactorFactory
{
    /**
     * Generates a TwoFactorAuth object with the website title as its label
     * 
     * @return TwoFactorAuth
     */
    public static function generate()
    {
        $title = $GLOBALS['TL_CONFIG']['websiteTitle'];
        return new TwoFactorAuth($title);
    }

    /**
     * Verifies a code with the configured discrepancy
     * 
     * @param string $secret The secret to verify the code with
     * @param string $code The code to verify
     * @return boolean
     */
    public static function verifyCode($secret, $code)
    {
        $discrepancy = (int) \Config::get('tfaTOTPdiscrepancy');

        if ($discrepancy < 0) {
            // Make sure the discrepancy is positive, otherwise we're stuck in an infinite loop.
            $discrepancy = -$discrepancy;
        }

        $auth = self::generate();
        return $auth->verifyCode($secret, $code, $discrepancy);
    }

    /**
     * Checks if a user should be redirected to the 2FA setup form
     * 
     * @return boolean
     */
    public static function tfaSetupRequired(BackendUser $user)
    {
        $forceTFA = \Config::get('forceTFA');

        return !$user->pwChange && ($user->tfaChange || (!$user->tfaSecret && $forceTFA));
    }
}