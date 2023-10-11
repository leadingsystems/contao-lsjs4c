<?php

namespace LeadingSystems\Lsjs4c;

/*
 * Include the lsjs core in FE and BE
 * @toDo remove TL_MODE for Contao 5
 * use: System::getContainer()->get('contao.routing.scope_matcher')->isBackendRequest(System::getContainer()->get('request_stack')->getCurrentRequest() ?? Request::create(''))
 */
if (TL_MODE === 'BE') {
    $GLOBALS['TL_HOOKS']['outputBackendTemplate'][] = array('LeadingSystems\Lsjs4c\lsjs4c_controller', 'getBackendLsjs');
}

$GLOBALS['TL_HOOKS']['getPageLayout'][] = array('LeadingSystems\Lsjs4c\lsjs4c_controller', 'getLayoutSettingsForGlobalUse');
$GLOBALS['TL_HOOKS']['generatePage'][] = array('LeadingSystems\Lsjs4c\lsjs4c_controller', 'insertLsjs');