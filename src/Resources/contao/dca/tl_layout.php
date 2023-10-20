<?php

namespace LeadingSystems\Lsjs4c;

$GLOBALS['TL_DCA']['tl_layout']['palettes']['default'] .= ';{lsjs4c_legend},lsjs4c_loadLsjs,lsjs4c_appToLoad,lsjs4c_appToLoadTextPath,lsjs4c_appCustomizationToLoad,lsjs4c_appCustomizationToLoadTextPath,lsjs4c_coreCustomizationToLoad,lsjs4c_coreCustomizationToLoadTextPath,lsjs4c_debugMode,lsjs4c_noMinifier';

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_loadLsjs'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_loadLsjs'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'m12')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appToLoad'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appToLoad'],
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => array(
		'multiple' => false,
		'tl_class'=>'clr',
		'files' => false,
		'filesOnly' => false,
		'fieldType' => 'radio'
	)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appToLoadTextPath'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appToLoadTextPath'],
	'exclude' => true,
	'inputType' => 'text',
	'eval' => array(
		'tl_class'=>'clr'
	)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appCustomizationToLoad'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appCustomizationToLoad'],
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => array(
		'multiple' => false,
		'tl_class'=>'clr',
		'files' => false,
		'filesOnly' => false,
		'fieldType' => 'radio'
	)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appCustomizationToLoadTextPath'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appCustomizationToLoadTextPath'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'tl_class'=>'clr'
    )
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_coreCustomizationToLoad'] = array(
	'label' => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_coreCustomizationToLoad'],
	'exclude' => true,
	'inputType' => 'fileTree',
	'eval' => array(
		'multiple' => false,
		'tl_class'=>'clr',
		'files' => false,
		'filesOnly' => false,
		'fieldType' => 'radio'
	)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_coreCustomizationToLoadTextPath'] = array(
    'label' => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_coreCustomizationToLoadTextPath'],
    'exclude' => true,
    'inputType' => 'text',
    'eval' => array(
        'tl_class'=>'clr'
    )
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_debugMode'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_debugMode'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'m12')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_noMinifier'] = array(
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_noMinifier'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'m12')
);