<?php

$GLOBALS['TL_DCA']['tl_settings']['fields']['tfaTOTPdiscrepancy'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tfaTOTPdiscrepancy'],
    'inputType' => 'text',
    'eval' => ['minval' => '0', 'rgxp' => 'natural', 'tl_class' => 'w50'],
    'default' => 1,
];

$GLOBALS['TL_DCA']['tl_settings']['fields']['forceTFA'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['forceTFA'],
    'inputType' => 'checkbox',
    'default' => false,
];

// Add the TOTP discrepancy and force TFA settings
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace(
    'allowedTags', 
    'allowedTags,forceTFA,tfaTOTPdiscrepancy', 
    $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);
