<?php


namespace LeadingSystems\LSJS4CBundle\EventListener;


use Contao\System;

class GeneratePageListener
{
    private string $projectDir;

    public function __construct(string $projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public function insertLsjs(): void
    {
        if (!isset($GLOBALS['lsjs4c_globals']['lsjs4c_loadLsjs']) || !$GLOBALS['lsjs4c_globals']['lsjs4c_loadLsjs']) {
            return;
        }

        /*
         * Load the lsjs core
         */
        $str_coreCustomizationPath = $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoadTextPath'] ?: (is_array($GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad'][0] : $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad']);

        require_once($this->projectDir . "/assets/lsjs/core/appBinder/binderController.php");

        $arr_config = [
            'pathForRenderedFiles' => $this->projectDir . '/assets/js',
            'includeAppModules' => 'no',
            'includeApp' => 'no',
            'pathToCoreCustomization' => ($str_coreCustomizationPath ? $this->projectDir . '/' . $str_coreCustomizationPath : ''),
            'debug' => ($GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] ? '1' : ''),
            'no-minifier' => ($GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] ? '1' : ''),
        ];

        $binderController = new \lsjs_binderController($arr_config);
        $GLOBALS['TL_JAVASCRIPT'][] = str_replace($this->projectDir, '', $binderController->getPathToRenderedFile());

        /*
         * Load the lsjs apps
         */
        $str_appPath = $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoadTextPath'] ?: (is_array($GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad'][0] : $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad']);
        $str_appCustomizationPath = $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoadTextPath'] ?: (is_array($GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad']) ? $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad'][0] : $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad']);


        $arr_config = [
            'pathForRenderedFiles' => $this->projectDir . '/assets/js',
            'pathToApp' => $this->projectDir . '/' . $str_appPath,
            'includeCore' => 'no',
            'includeCoreModules' => 'no',
            'pathToAppCustomization' => ($str_appCustomizationPath ? $this->projectDir . '/' . $str_appCustomizationPath : ''),
            'debug' => ($GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] ? '1' : ''),
            'no-minifier' => ($GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] ? '1' : ''),
        ];

        $binderController = new \lsjs_binderController($arr_config);
        $GLOBALS['TL_JAVASCRIPT'][] = str_replace($this->projectDir, '', $binderController->getPathToRenderedFile());
    }
}