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
		$GLOBALS['TL_JAVASCRIPT'][] = 'assets/lsjs/core/appBinder/binder.php?output=js&includeAppModules=no&includeApp=no';
		$GLOBALS['TL_CSS'][] = 'assets/lsjs/core/appBinder/binder.php?output=css&includeAppModules=no&includeApp=no&includeMasterStyleFiles=no';

		/*
		 * Load the lsjs apps
		 */
		foreach ($GLOBALS['lsjs4c_globals']['lsjs4c_appsToLoad'] as $str_appPath) {
			$arr_hashesOfModulesToExclude = $this->getHashesOfModulesToExclude($str_appPath);

			$GLOBALS['TL_JAVASCRIPT'][] = 'assets/lsjs/core/appBinder/binder.php?output=js&pathToApp='.urldecode($this->str_folderUpPrefix.$str_appPath).'&includeCore=no&includeCoreModules=no'.(count($arr_hashesOfModulesToExclude) ? '&blacklist='.implode(',', $arr_hashesOfModulesToExclude) : '');
			$GLOBALS['TL_CSS'][] = 'assets/lsjs/core/appBinder/binder.php?output=css&pathToApp='.urldecode($this->str_folderUpPrefix.$str_appPath).'&includeCore=no&includeCoreModules=no'.(count($arr_hashesOfModulesToExclude) ? '&blacklist='.implode(',', $arr_hashesOfModulesToExclude) : '');
		}
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

		$arr_appsToLoad = deserialize($objLayout->lsjs4c_appsToLoad, true);
		$arr_appPaths = array();
		foreach ($arr_appsToLoad as $bin_uuid) {
			$arr_appPaths[] = ls_getFilePathFromVariableSources($bin_uuid);
		}
		$GLOBALS['lsjs4c_globals']['lsjs4c_appsToLoad'] = $arr_appPaths;

		$arr_modulesToExclude = deserialize($objLayout->lsjs4c_modulesToExclude, true);
		$arr_modulePaths = array();
		foreach ($arr_modulesToExclude as $bin_uuid) {
			$arr_modulePaths[] = ls_getFilePathFromVariableSources($bin_uuid);
		}
		$GLOBALS['lsjs4c_globals']['lsjs4c_modulesToExclude'] = $arr_modulePaths;
	}

}
