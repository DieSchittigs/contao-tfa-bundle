<?php

namespace DieSchittigs\TwoFactorAuth;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use DieSchittigs\TwoFactorAuth\DependencyInjection\TwoFactorAuthExtension;

class TwoFactorAuthBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new TwoFactorAuthExtension();
    }
}