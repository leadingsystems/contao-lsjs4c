<?php

namespace LeadingSystems\Lsjs4c;

use function LeadingSystems\Helpers\ls_getFilePathFromVariableSources;
use Contao\System;

class lsjs4c_controller extends \Controller {
    protected $str_folderUpPrefix = '_dup4_/';

    protected static $objInstance;

    protected function __construct() {
        parent::__construct();
    }

    private function __clone() {

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
        $str_coreCustomizationPath = $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoadTextPath'] ?: (is_array($GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad'][0] : $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad']);

        require_once(System::getContainer()->getParameter('kernel.project_dir')."/assets/lsjs/core/appBinder/binderController.php");

        $arr_config = [
            "includeAppModules" => 'no',
            "includeApp" => 'no',
            "pathToCoreCustomization" => ($str_coreCustomizationPath ? urldecode($this->str_folderUpPrefix.$str_coreCustomizationPath) : ''),
            "debug" => ($GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] ? '1' : ''),
            "no-cache" => ($GLOBALS['lsjs4c_globals']['lsjs4c_noCache'] ? '1' : ''),
            "no-minifier" => ($GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] ? '1' : ''),
        ];

        $binderController = new \lsjs_binderController($arr_config);
        $GLOBALS['TL_JAVASCRIPT'][] = "/assets/lsjs/core/appBinder/".$binderController->getJS();

        /*
         * Load the lsjs apps
         */
        $str_appPath = $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoadTextPath'] ?: (is_array($GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad'][0] : $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad']);
        $str_appCustomizationPath = $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoadTextPath'] ?: (is_array($GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad'][0] : $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad']);
        $arr_hashesOfModulesToExclude = $this->getHashesOfModulesToExclude(count($GLOBALS['lsjs4c_globals']['lsjs4c_modulesToExcludeTextPath']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_modulesToExcludeTextPath'] : $GLOBALS['lsjs4c_globals']['lsjs4c_modulesToExclude'], $str_appPath);


        $arr_config = [
            "pathToApp" => urldecode($this->str_folderUpPrefix.$str_appPath),
            "includeCore" => 'no',
            "includeCoreModules" => 'no',
            "pathToAppCustomization" => ($str_appCustomizationPath ? urldecode($this->str_folderUpPrefix.$str_appCustomizationPath): ''),
            "blacklist" => (count($arr_hashesOfModulesToExclude) ? implode(',', $arr_hashesOfModulesToExclude) : ''),
            "debug" => ($GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] ? '1' : ''),
            "no-cache" => ($GLOBALS['lsjs4c_globals']['lsjs4c_noCache'] ? '1' : ''),
            "no-minifier" => ($GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] ? '1' : ''),
        ];

        $binderController = new \lsjs_binderController($arr_config);
        $GLOBALS['TL_JAVASCRIPT'][] = "/assets/lsjs/core/appBinder/".$binderController->getJS();
    }

    protected function getHashesOfModulesToExclude($arr_modulesToExclude, $str_appPath) {
        $arr_hashesOfModulesToExclude = array();

        /*
         * Look if a module path begins with the given app path and if it does,
         * generate the module path hash that will then be used for the blacklist
         * parameter in the binder request
         */
        foreach ($arr_modulesToExclude as $str_modelPath) {
            if (strpos($str_modelPath, $str_appPath.'/') === 0) {
                $arr_hashesOfModulesToExclude[] = md5($this->replaceDirectoryUpAbbreviation($this->str_folderUpPrefix.$str_modelPath));
            }
        }

        return $arr_hashesOfModulesToExclude;
    }

    public function getLayoutSettingsForGlobalUse(\PageModel $objPage, \LayoutModel $objLayout, \PageRegular $objPageRegular) {
        $GLOBALS['lsjs4c_globals']['lsjs4c_loadLsjs'] = $objLayout->lsjs4c_loadLsjs;

        $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad'] = ls_getFilePathFromVariableSources($objLayout->lsjs4c_appToLoad);

        $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoadTextPath'] = $objLayout->lsjs4c_appToLoadTextPath;

        $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad'] = ls_getFilePathFromVariableSources($objLayout->lsjs4c_appCustomizationToLoad);

        $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoadTextPath'] = $objLayout->lsjs4c_appCustomizationToLoadTextPath;

        $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad'] = ls_getFilePathFromVariableSources($objLayout->lsjs4c_coreCustomizationToLoad);

        $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoadTextPath'] = $objLayout->lsjs4c_coreCustomizationToLoadTextPath;

        $arr_modulesToExclude = deserialize($objLayout->lsjs4c_modulesToExclude, true);
        $arr_modulePaths = array();
        foreach ($arr_modulesToExclude as $bin_uuid) {
            $arr_modulePaths[] = ls_getFilePathFromVariableSources($bin_uuid);
        }
        $GLOBALS['lsjs4c_globals']['lsjs4c_modulesToExclude'] = $arr_modulePaths;

        $GLOBALS['lsjs4c_globals']['lsjs4c_modulesToExcludeTextPath'] = empty(trim($objLayout->lsjs4c_modulesToExcludeTextPath)) ? [] : array_map('trim', explode(',', $objLayout->lsjs4c_modulesToExcludeTextPath));

        $GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] = $objLayout->lsjs4c_debugMode;

        $GLOBALS['lsjs4c_globals']['lsjs4c_noCache'] = $objLayout->lsjs4c_noCache;

        $GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] = $objLayout->lsjs4c_noMinifier;
    }



    public static function getBackendLsjs($str_content, $str_template) {
        if ($str_template !== 'be_main') {
            return $str_content;
        }

        require_once(System::getContainer()->getParameter('kernel.project_dir')."/assets/lsjs/core/appBinder/binderController.php");

        $arr_config = [
            "includeApp" => "no",
            "includeAppModules" => "no"
        ];

        $binderController = new \lsjs_binderController($arr_config);
        $str_output = "/assets/lsjs/core/appBinder/".$binderController->getJS();
        $GLOBALS['TL_JAVASCRIPT'][] = $str_output;


        ob_start();
        ?>
        <script src="<?= $str_output ?>"></script>
        <?php
        return str_replace('</head>', ob_get_clean()."\r\n</head>", $str_content);
    }

    protected function replaceDirectoryUpAbbreviation($str_url) {
        $str_url = preg_replace_callback(
            '/_dup([0-9]+?)_/',
            function($arr_matches) {
                $arr_dirUp = array();
                for ($i = 1; $i <= $arr_matches[1]; $i++) {
                    $arr_dirUp[] = '..';
                }
                $str_dirUpPrefix = implode('/', $arr_dirUp);

                return $str_dirUpPrefix;
            },
            $str_url
        );

        return $str_url;
    }
}
