<?php

namespace LeadingSystems\Lsjs4c;

use Contao\StringUtil;
use Contao\System;
use function LeadingSystems\Helpers\ls_getFilePathFromVariableSources;

class lsjs4c_controller extends \Controller {
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

        $str_projectDir = System::getContainer()->getParameter('kernel.project_dir');

        require_once($str_projectDir . "/assets/lsjs/core/appBinder/binderController.php");

        $arr_config = [
            'pathForRenderedFiles' => $str_projectDir . '/assets/js',
            'includeAppModules' => 'no',
            'includeApp' => 'no',
            'pathToCoreCustomization' => ($str_coreCustomizationPath ? $str_projectDir . '/' . $str_coreCustomizationPath : ''),
            'debug' => ($GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] ? '1' : ''),
            'no-minifier' => ($GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] ? '1' : ''),
        ];

        $binderController = new \lsjs_binderController($arr_config);
        $GLOBALS['TL_JAVASCRIPT'][] = str_replace($str_projectDir, '', $binderController->getPathToRenderedFile());

        /*
         * Load the lsjs apps
         */
        $str_appPath = $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoadTextPath'] ?: (is_array($GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad'][0] : $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad']);
        $str_appCustomizationPath = $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoadTextPath'] ?: (is_array($GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad'][0] : $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad']);


        $arr_config = [
            'pathForRenderedFiles' => $str_projectDir . '/assets/js',
            'pathToApp' => $str_projectDir . '/' . $str_appPath,
            'includeCore' => 'no',
            'includeCoreModules' => 'no',
            'pathToAppCustomization' => ($str_appCustomizationPath ? $str_projectDir . '/' . $str_appCustomizationPath : ''),
            'debug' => ($GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] ? '1' : ''),
            'no-minifier' => ($GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] ? '1' : ''),
        ];

        $binderController = new \lsjs_binderController($arr_config);
        $GLOBALS['TL_JAVASCRIPT'][] = str_replace($str_projectDir, '', $binderController->getPathToRenderedFile());
    }

    public function getLayoutSettingsForGlobalUse(\PageModel $objPage, \LayoutModel $objLayout, \PageRegular $objPageRegular) {
        $GLOBALS['lsjs4c_globals']['lsjs4c_loadLsjs'] = $objLayout->lsjs4c_loadLsjs;

        $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad'] = ls_getFilePathFromVariableSources($objLayout->lsjs4c_appToLoad);

        $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoadTextPath'] = $objLayout->lsjs4c_appToLoadTextPath;

        $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad'] = ls_getFilePathFromVariableSources($objLayout->lsjs4c_appCustomizationToLoad);

        $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoadTextPath'] = $objLayout->lsjs4c_appCustomizationToLoadTextPath;

        $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad'] = ls_getFilePathFromVariableSources($objLayout->lsjs4c_coreCustomizationToLoad);

        $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoadTextPath'] = $objLayout->lsjs4c_coreCustomizationToLoadTextPath;

        $GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] = $objLayout->lsjs4c_debugMode;

        $GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] = $objLayout->lsjs4c_noMinifier;
    }
}
