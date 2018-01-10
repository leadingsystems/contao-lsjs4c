<?php

namespace LeadingSystems\Lsjs4c;

/*
 * Include the lsjs core in FE and BE
 */
if (TL_MODE === 'BE') {
	$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/ls_lsjs4c/pub/lsjs/core/appBinder/binder.php?output=js&includeAppModules=no&includeApp=no';
	$GLOBALS['TL_CSS'][] = 'system/modules/ls_lsjs4c/pub/lsjs/core/appBinder/binder.php?output=css&includeAppModules=no&includeApp=no&includeMasterStyleFiles=no';
}

$GLOBALS['TL_HOOKS']['getPageLayout'][] = array('LeadingSystems\Lsjs4c\lsjs4c_controller', 'getLayoutSettingsForGlobalUse');
$GLOBALS['TL_HOOKS']['generatePage'][] = array('LeadingSystems\Lsjs4c\lsjs4c_controller', 'insertLsjs');