<?php

namespace LeadingSystems\Lsjs4c;

$GLOBALS['TL_HOOKS']['getPageLayout'][] = array('LeadingSystems\Lsjs4c\lsjs4c_controller', 'getLayoutSettingsForGlobalUse');
$GLOBALS['TL_HOOKS']['generatePage'][] = array('LeadingSystems\Lsjs4c\lsjs4c_controller', 'insertLsjs');