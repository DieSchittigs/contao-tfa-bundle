<?php

// Add the tfaSecret field to the tl_user table.
$GLOBALS['TL_DCA']['tl_user']['fields']['tfaSecret'] = [
    'label'         => &$GLOBALS['TL_LANG']['tl_user']['tfaSecret'],
    'inputType'     => 'tfaSecret',
    'sql'           => "varchar(64) NOT NULL default ''",
    'save_callback' => [
        ['TwoFactorFieldListener', 'saveSecret'],
    ],
];

// Add the tfaSecret field to the login palette
$GLOBALS['TL_DCA']['tl_user']['palettes']['login'] .= ';{tfa_title},tfaSecret';

// Add the tfaChange field to the tl_user table.
$GLOBALS['TL_DCA']['tl_user']['fields']['tfaChange'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['tfaChange'],
    'inputType' => 'checkbox',
    'excluded'  => true,
    'filter'    => true,
    'sql'       => "char(1) NOT NULL default ''",
    'eval'      => ['tl_class' => 'w50']
];

// Add the tfaChange field to the tl_user table.
$GLOBALS['TL_DCA']['tl_user']['fields']['tfaReset'] = [
    'label'     => &$GLOBALS['TL_LANG']['tl_user']['tfaReset'],
    'inputType' => 'checkbox',
    'excluded'  => true,
    'filter'    => true,
    'sql'       => "char(1) NOT NULL default ''",
    'eval'      => ['tl_class' => 'w50'],
    'save_callback' => [
        ['TwoFactorFieldListener', 'saveForceChangeField'],
    ],
];

foreach ($GLOBALS['TL_DCA']['tl_user']['palettes'] as &$palette) {
    $palette = str_replace('{admin_legend}', '{tfa_title:hide},tfaChange,tfaReset;{admin_legend}', $palette);
}