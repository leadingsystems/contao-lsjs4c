<?php

namespace LeadingSystems\Lsjs4c;

$GLOBALS['TL_DCA']['tl_layout']['palettes']['default'] .= ';{lsjs4c_legend},lsjs4c_loadLsjs,lsjs4c_appsToLoad,lsjs4c_modulesToExclude,lsjs4c_debugMode';

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_loadLsjs'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_loadLsjs'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'m12')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appsToLoad'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appsToLoad'],
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => array(
		'multiple' => true,
		'tl_class'=>'clr',
		'files' => false,
		'filesOnly' => false,
		'fieldType' => 'checkbox'
	)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_modulesToExclude'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_modulesToExclude'],
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => array(
		'multiple' => true,
		'tl_class'=>'clr',
		'files' => false,
		'filesOnly' => false,
		'fieldType' => 'checkbox'
	)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_debugMode'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_debugMode'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'m12')
);