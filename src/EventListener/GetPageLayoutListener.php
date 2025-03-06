<?php

namespace LeadingSystems\LSJS4CBundle\EventListener;

use Contao\LayoutModel;
use Contao\PageModel;
use Contao\PageRegular;

class GetPageLayoutListener
{
    public function getLayoutSettingsForGlobalUse(PageModel $pageModel, LayoutModel $layout, PageRegular $pageRegular): void
    {
        $GLOBALS['lsjs4c_globals']['lsjs4c_loadLsjs'] = $layout->lsjs4c_loadLsjs;

        $GLOBALS['lsjs4c_globals']['lsjs4c_appCustomizationToLoad'] = $layout->lsjs4c_appCustomizationToLoad;
        $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationToLoad'] = $layout->lsjs4c_coreCustomizationToLoad;

        $GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] = $layout->lsjs4c_debugMode;

        $GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] = $layout->lsjs4c_noMinifier;

    }
}