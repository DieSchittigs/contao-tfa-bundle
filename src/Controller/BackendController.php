<?php

namespace DieSchittigs\TwoFactorAuth\Controller;

use Contao\CoreBundle\Controller\BackendController as ContaoBackendController;

class BackendController extends ContaoBackendController
{
    /**
     * Runs the action for when users need to authenticate themselves with a code
     * 
	 * @return Symfony\Component\HttpFoundation\Response
     */
    public function enterCode()
    {
        $this->container->get('contao.framework')->initialize();

        $controller = new BackendEnterTFACode();

        return $controller->run();
    }

    /**
     * Runs the action for when users are required to set up 2FA
     * 
	 * @return Symfony\Component\HttpFoundation\Response
     */
    public function setupTFA()
    {
        $this->container->get('contao.framework')->initialize();

        $controller = new BackendSetupTFA();

        return $controller->run();
    }
}