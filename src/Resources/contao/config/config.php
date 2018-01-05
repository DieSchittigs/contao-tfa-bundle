<?php

$GLOBALS['BE_FFL']['tfaSecret'] = 'TwoFactorSecretWidget';
$GLOBALS['BE_FFL']['tfaReset'] = 'TwoFactorResetWidget';

$GLOBALS['TL_HOOKS']['storeFormData'][] = ['TwoFactorFieldListener', 'store'];