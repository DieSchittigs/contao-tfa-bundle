<?php

namespace DieSchittigs\TwoFactorAuth;

use RobThree\Auth\TwoFactorAuth;


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
        $discrepancy = (int) $GLOBALS['TL_CONFIG']['tfaTtopDiscrepancy'];

        if ($discrepancy < 0) {
            // Make sure the discrepancy is positive, otherwise we're stuck in an infinite loop.
            $discrepancy = -$discrepancy;
        }

        $auth = self::generate();
        return $auth->verifyCode($secret, $code, $discrepancy);
    }
}