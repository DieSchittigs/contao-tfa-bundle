<?php

// Add the tfaSecret field to the tl_user table.
$GLOBALS['TL_DCA']['tl_user']['fields']['tfaSecret'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_user']['tfaSecret'],
    'inputType'     => 'tfaSecret',
    'sql'           => "varchar(64) NOT NULL default ''",
    'save_callback' => [
        ['TwoFactorSaveListener', 'save'],
    ],
];

// Add the tfaSecret field to the login palette
$GLOBALS['TL_DCA']['tl_user']['palettes']['login'] .= ';{tfa_title},tfaSecret';

// Add the tfaChange field to the tl_user table.
$GLOBALS['TL_DCA']['tl_user']['fields']['tfaChange'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_user']['tfaChange'],
    'excluded'      => true,
    'inputType'     => 'checkbox',
    'filter'        => true,
    'sql'           => "char(1) NOT NULL default ''",
];