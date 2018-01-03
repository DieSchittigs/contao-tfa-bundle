<?php

$GLOBALS['TL_DCA']['tl_user']['fields']['tfaSecret'] = [
    'label'   => '2FA Secret',
    'exclude' => true,
    'sql'     => "varchar(64) NOT NULL default ''"
];

$GLOBALS['TL_DCA']['tl_user']['palettes']['admin'] .= ',tfaSecret';

var_dump($GLOBALS['TL_DCA']['tl_user']);
die();