<?php

namespace LeadingSystems\Lsjs4c;

/*
 * Include the lsjs core in FE and BE
 */

use Contao\System;

if (System::getContainer()->get('merconis.routing.scope_matcher')->isBackend()) {
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('LeadingSystems\Lsjs4c\lsjs4c_controller', 'getBackendLsjs');
}

$GLOBALS['TL_HOOKS']['getPageLayout'][] = array('LeadingSystems\Lsjs4c\lsjs4c_controller', 'getLayoutSettingsForGlobalUse');
$GLOBALS['TL_HOOKS']['generatePage'][] = array('LeadingSystems\Lsjs4c\lsjs4c_controller', 'insertLsjs');