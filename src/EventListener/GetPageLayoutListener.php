<?php

namespace LeadingSystems\LSJS4CBundle\EventListener;

use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;
use function LeadingSystems\Helpers\ls_getFilePathFromVariableSources;

class GetPageLayoutListener
{
    public function getLayoutSettingsForGlobalUse(PageModel $pageModel, LayoutModel $layout, PageRegular $pageRegular): void
    {
        $GLOBALS['lsjs4c_globals']['lsjs4c_loadLsjs'] = $layout->lsjs4c_loadLsjs;

        $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoad'] = ls_getFilePathFromVariableSources($layout->lsjs4c_appToLoad);

        $GLOBALS['lsjs4c_globals']['lsjs4c_appToLoadTextPath'] = $layout->lsjs4c_appToLoadTextPath;

        $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad'] = ls_getFilePathFromVariableSources($layout->lsjs4c_appCustomizationToLoad);

        $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoadTextPath'] = $layout->lsjs4c_appCustomizationToLoadTextPath;

        $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad'] = ls_getFilePathFromVariableSources($layout->lsjs4c_coreCustomizationToLoad);

        $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoadTextPath'] = $layout->lsjs4c_coreCustomizationToLoadTextPath;

        $GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] = $layout->lsjs4c_debugMode;

        $GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] = $layout->lsjs4c_noMinifier;

    }
}