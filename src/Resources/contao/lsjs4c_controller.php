<?php

namespace LeadingSystems\Lsjs4c;
use function LeadingSystems\Helpers\ls_getFilePathFromVariableSources;

class lsjs4c_controller extends \Controller {
	protected $str_folderUpPrefix = '_dup4_/';

	protected static $objInstance;

	protected function __construct() {
		parent::__construct();
	}

	final private function __clone() {
		
	}

	public static function getInstance() {
		if (!is_object(self::$objInstance)) {
			self::$objInstance = new self();
		}
		return self::$objInstance;
	}

	public function insertLsjs() {
		if (!isset($GLOBALS['lsjs4c_globals']['lsjs4c_loadLsjs']) || !$GLOBALS['lsjs4c_globals']['lsjs4c_loadLsjs']) {
			return;
		}

		/*
		 * Load the lsjs core
		 */
		$str_coreCustomizationPath = is_array($GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad'][0] : $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad'];

		$GLOBALS['TL_JAVASCRIPT'][] =
			'assets/lsjs/core/appBinder/binder.php?output=js&includeAppModules=no&includeApp=no'
			.($str_coreCustomizationPath ? '&pathToCoreCustomization='.urldecode($this->str_folderUpPrefix.$str_coreCustomizationPath) : '')
			.($GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] ? '&debug=1' : '')
			.($GLOBALS['lsjs4c_globals']['lsjs4c_noCache'] ? '&no-cache=1' : '')
			.($GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] ? '&no-minifier=1' : '');

		/*
		 * Load the lsjs apps
		 */
		$str_appPath = is_array($GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad'][0] : $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad'];
		$str_appCustomizationPath = is_array($GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad'][0] : $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad'];
		$arr_hashesOfModulesToExclude = $this->getHashesOfModulesToExclude($str_appPath);

		$GLOBALS['TL_JAVASCRIPT'][] =
			'assets/lsjs/core/appBinder/binder.php?output=js&pathToApp='.urldecode($this->str_folderUpPrefix.$str_appPath)
			.'&includeCore=no&includeCoreModules=no'
			.($str_appCustomizationPath ? '&pathToAppCustomization='.urldecode($this->str_folderUpPrefix.$str_appCustomizationPath) : '')
			.(count($arr_hashesOfModulesToExclude) ? '&blacklist='.implode(',', $arr_hashesOfModulesToExclude) : '')
			.($GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] ? '&debug=1' : '')
			.($GLOBALS['lsjs4c_globals']['lsjs4c_noCache'] ? '&no-cache=1' : '')
			.($GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] ? '&no-minifier=1' : '');
	}

	protected function getHashesOfModulesToExclude($str_appPath) {
		$arr_hashesOfModulesToExclude = array();
		
		/*
		 * Look if a module path begins with the given app path and if it does,
		 * generate the module path hash that will then be used for the blacklist
		 * parameter in the binder request
		 */
		foreach ($GLOBALS['lsjs4c_globals']['lsjs4c_modulesToExclude'] as $str_modelPath) {
			if (strpos($str_modelPath, $str_appPath.'/') === 0) {
				$arr_hashesOfModulesToExclude[] = md5($this->str_folderUpPrefix.$str_modelPath);
			}
		}
		
		return $arr_hashesOfModulesToExclude;
	}

	public function getLayoutSettingsForGlobalUse(\PageModel $objPage, \LayoutModel $objLayout, \PageRegular $objPageRegular) {
		$GLOBALS['lsjs4c_globals']['lsjs4c_loadLsjs'] = $objLayout->lsjs4c_loadLsjs;
		
		$GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad'] = ls_getFilePathFromVariableSources($objLayout->lsjs4c_appToLoad);

		$GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad'] = ls_getFilePathFromVariableSources($objLayout->lsjs4c_appCustomizationToLoad);

		$GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad'] = ls_getFilePathFromVariableSources($objLayout->lsjs4c_coreCustomizationToLoad);

		$arr_modulesToExclude = deserialize($objLayout->lsjs4c_modulesToExclude, true);
		$arr_modulePaths = array();
		foreach ($arr_modulesToExclude as $bin_uuid) {
			$arr_modulePaths[] = ls_getFilePathFromVariableSources($bin_uuid);
		}
		$GLOBALS['lsjs4c_globals']['lsjs4c_modulesToExclude'] = $arr_modulePaths;

		$GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] = $objLayout->lsjs4c_debugMode;

		$GLOBALS['lsjs4c_globals']['lsjs4c_noCache'] = $objLayout->lsjs4c_noCache;

		$GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] = $objLayout->lsjs4c_noMinifier;
	}



    public static function getBackendLsjs($str_content, $str_template) {
        if ($str_template !== 'be_main') {
            return $str_content;
        }

        ob_start();
        ?>
        <script src="assets/lsjs/core/appBinder/binder.php?output=js&includeAppModules=no&includeApp=no"></script>
        <?php
        return str_replace('</head>', ob_get_clean()."\r\n</head>", $str_content);
    }
}
