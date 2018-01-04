<?php

namespace Contao;

use RobThree\Auth\TwoFactorAuth;

class TwoFactorAuthentication extends \Backend
{
    public function __construct()
    {
        $this->import('BackendUser', 'user');

        parent::__construct();
    }

    public static function generateAuthenticator()
    {
        $title = $GLOBALS['TL_CONFIG']['websiteTitle'];
        return new TwoFactorAuth($title);
    }

    public static function verifyCode($secret, $code)
    {
        $auth = self::generateAuthenticator();
        return $auth->verifyCode($secret, $code);
    }
}