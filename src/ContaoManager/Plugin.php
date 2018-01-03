<?php

namespace DieSchittigs\TwoFactorAuth\ContaoManager;

use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use DieSchittigs\TwoFactorAuth\TwoFactorAuthBundle;

class Plugin implements BundlePluginInterface
{
    /**
     * {@inheritdoc}
     */
    public function getBundles(ParserInterface $parser)
    {
        return [
            BundleConfig::create('DieSchittigs\TwoFactorAuth\TwoFactorAuthBundle') 
                ->setLoadAfter(['Contao\CoreBundle\ContaoCoreBundle']) 
        ];
    }
}
