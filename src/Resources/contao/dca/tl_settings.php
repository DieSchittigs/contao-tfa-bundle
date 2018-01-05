<?php

$GLOBALS['TL_DCA']['tl_settings']['fields']['tfaTtopDiscrepancy'] = [
    'label' => &$GLOBALS['TL_LANG']['tl_settings']['tfaTtopDiscrepancy'],
    'inputType' => 'text',
    'eval' => ['minval' => '0', 'rgxp' => 'natural', 'tl_class' => 'w50'],
    'default' => '1',
];

// Add the TTOP discrepancy setting
$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] = str_replace(
    'allowedTags', 
    'allowedTags,tfaTtopDiscrepancy', 
    $GLOBALS['TL_DCA']['tl_settings']['palettes']['default']
);