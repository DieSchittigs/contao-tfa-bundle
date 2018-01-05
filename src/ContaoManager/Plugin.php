<?php

namespace DieSchittigs\TwoFactorAuth\ContaoManager;

use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use DieSchittigs\TwoFactorAuth\TwoFactorAuthBundle;
use Symfony\Component\HttpKernel\KernelInterface;

class Plugin implements BundlePluginInterface, RoutingPluginInterface
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

    /**
     * {@inheritdoc}
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel)
    {
        $routingFile = __DIR__ . '/../Resources/config/routing.yml';

        return $resolver
            ->resolve($routingFile)
            ->load($routingFile);
    }
}
