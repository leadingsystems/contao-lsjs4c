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

        $arr_corePaths = unserialize($GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationsToLoad']);

        // Path entrys are relative to the root path, this must be changed
        if (is_array($arr_corePaths)) {
            foreach ($arr_corePaths as $key => $path) {
                $arr_corePaths[$key] = $this->projectDir . '/' . $path;
            }
        }

        require_once($this->projectDir . "/assets/lsjs/core/appBinder/binderController.php");

        $arr_config = [
            'pathForRenderedFiles' => $this->projectDir . '/assets/js',
            'includeAppModules' => 'no',
            'includeApp' => 'no',
            'pathsToCoreCustomizations' => $arr_corePaths,
            'debug' => ($GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] ? '1' : ''),
            'no-minifier' => ($GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] ? '1' : ''),
        ];

        $binderController = new \lsjs_binderController($arr_config);
        $GLOBALS['TL_JAVASCRIPT'][] = str_replace($this->projectDir, '', $binderController->getPathToRenderedFile());

        /*
         * Load the lsjs apps
         */

        $arr_appPaths = unserialize($GLOBALS['lsjs4c_globals']['lsjs4c_appsToLoad']);

        // Path entrys are relative to the root path, this must be changed
        if (is_array($arr_appPaths)) {
            foreach ($arr_appPaths as $key => $path) {
                $arr_appPaths[$key] = $this->projectDir . '/' . $path;
            }
        }

        $arr_config = [
            'pathForRenderedFiles' => $this->projectDir . '/assets/js',
            'includeCore' => 'no',
            'includeCoreModules' => 'no',
            'pathsToApps' => $arr_appPaths,
            'debug' => ($GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] ? '1' : ''),
            'no-minifier' => ($GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] ? '1' : ''),
        ];

        $binderController = new \lsjs_binderController($arr_config);
        $GLOBALS['TL_JAVASCRIPT'][] = str_replace($this->projectDir, '', $binderController->getPathToRenderedFile());
    }
}