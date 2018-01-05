<?php

namespace Contao;

class TwoFactorAuthTemplate extends BackendTemplate
{
    /**
     * {@inheritdoc}
     */
    public function parse()
    {
		$this->theme = \Backend::getTheme();
		$this->base = \Environment::get('base');
		$this->language = $GLOBALS['TL_LANGUAGE'];
		$this->messages = \Message::generate();
		$this->charset = \Config::get('characterSet');
		$this->action = ampersand(\Environment::get('request'));
		$this->loginButton = \StringUtil::specialchars($GLOBALS['TL_LANG']['MSC']['continue']);
        $this->jsDisabled = $GLOBALS['TL_LANG']['MSC']['jsDisabled'];
        
		$this->title = \StringUtil::specialchars(sprintf($GLOBALS['TL_LANG']['MSC']['loginTo'], \Config::get('websiteTitle')));
        $this->headline = $GLOBALS['TL_LANG']['tl_user']['tfa_title'];
        $this->feLink = $GLOBALS['TL_LANG']['MSC']['goBack'];
        $this->f2a_code = $GLOBALS['TL_LANG']['MSC']['tfaCode'];

        return parent::parse();
    }
}