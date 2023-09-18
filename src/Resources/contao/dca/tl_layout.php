<?php

namespace LeadingSystems\Lsjs4c;

$GLOBALS['TL_DCA']['tl_layout']['config']['onsubmit_callback'][] = ['LeadingSystems\Lsjs4c\tl_layout_controller', 'handleCache'];

$GLOBALS['TL_DCA']['tl_layout']['palettes']['default'] .= ';{lsjs4c_legend},lsjs4c_loadLsjs,lsjs4c_appToLoad,lsjs4c_appToLoadTextPath,lsjs4c_appCustomizationToLoad,lsjs4c_appCustomizationToLoadTextPath,lsjs4c_coreCustomizationToLoad,lsjs4c_coreCustomizationToLoadTextPath,lsjs4c_modulesToExclude,lsjs4c_modulesToExcludeTextPath,lsjs4c_debugMode,lsjs4c_noCache,lsjs4c_noMinifier';

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_loadLsjs'] = array(
    'sql'                     => "char(1) NOT NULL default ''",
	'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_loadLsjs'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('tl_class'=>'m12')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appToLoad'] = array(
    'sql'                   => "blob NULL",
	'label'                 => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appToLoad'],
	'exclude'               => true,
	'inputType'             => 'fileTree',
	'eval'                  => array(
		'multiple'              => false,
		'tl_class'              =>'clr',
		'files'                 => false,
		'filesOnly'             => false,
		'fieldType'             => 'radio'
	)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appToLoadTextPath'] = array(
    'sql'                   => "text NULL",
	'label'                 => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appToLoadTextPath'],
	'exclude'               => true,
	'inputType'             => 'text',
	'eval'                  => array(
		'tl_class'              =>'clr'
	)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appCustomizationToLoad'] = array(
    'sql'                   => "blob NULL",
	'label'                 => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appCustomizationToLoad'],
	'exclude'               => true,
	'inputType'             => 'fileTree',
	'eval'                  => array(
		'multiple'              => false,
		'tl_class'              =>'clr',
		'files'                 => false,
		'filesOnly'             => false,
		'fieldType'             => 'radio'
	)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appCustomizationToLoadTextPath'] = array(
    'sql'                   => "text NULL",
    'label'                 => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appCustomizationToLoadTextPath'],
    'exclude'               => true,
    'inputType'             => 'text',
    'eval'                  => array(
        'tl_class'              =>'clr'
    )
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_coreCustomizationToLoad'] = array(
    'sql'                   => "blob NULL",
	'label'                 => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_coreCustomizationToLoad'],
	'exclude'               => true,
	'inputType'             => 'fileTree',
	'eval'                  => array(
		'multiple'              => false,
		'tl_class'              =>'clr',
		'files'                 => false,
		'filesOnly'             => false,
		'fieldType'             => 'radio'
	)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_coreCustomizationToLoadTextPath'] = array(
    'sql'                   => "text NULL",
    'label'                 => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_coreCustomizationToLoadTextPath'],
    'exclude'               => true,
    'inputType'             => 'text',
    'eval'                  => array(
        'tl_class'              =>'clr'
    )
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_modulesToExclude'] = array(
    'sql'                   => "blob NULL",
	'label'                 => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_modulesToExclude'],
	'exclude'               => true,
	'inputType'             => 'fileTree',
	'eval'                  => array(
		'multiple'              => true,
		'tl_class'              =>'clr',
		'files'                 => false,
		'filesOnly'             => false,
		'fieldType'             => 'checkbox'
	)
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_modulesToExcludeTextPath'] = array(
    'sql'                   => "text NULL",
    'label'                 => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_modulesToExcludeTextPath'],
    'exclude'               => true,
    'inputType'             => 'text',
    'eval'                  => array(
        'tl_class'              =>'clr'
    )
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_debugMode'] = array(
    'sql'                   => "char(1) NOT NULL default ''",
	'label'                 => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_debugMode'],
	'exclude'               => true,
	'inputType'             => 'checkbox',
	'eval'                  => array('tl_class'=>'m12')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_noCache'] = array(
    'sql'                   => "char(1) NOT NULL default ''",
	'label'                 => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_noCache'],
	'exclude'               => true,
	'inputType'             => 'checkbox',
	'eval'                  => array('tl_class'=>'m12')
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_noMinifier'] = array(
    'sql'                   => "char(1) NOT NULL default ''",
	'label'                 => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_noMinifier'],
	'exclude'               => true,
	'inputType'             => 'checkbox',
	'eval'                  => array('tl_class'=>'m12')
);


class tl_layout_controller extends \Backend {
    public function handleCache($dc) {
        if ($dc->activeRecord->lsjs4c_noCache) {
            $arr_files = glob(TL_ROOT . '/assets/lsjs/cache/*');
            foreach($arr_files as $str_filePath){
                if(is_file($str_filePath)) {
                    unlink($str_filePath);
                }
            }
        }
    }
}