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

        $GLOBALS['lsjs4c_globals']['lsjs4c_appsToLoad'] = $layout->lsjs4c_appsToLoad;
        $GLOBALS['lsjs4c_globals']['lsjs4c_coreCustomizationsToLoad'] = $layout->lsjs4c_coreCustomizationsToLoad;

        $GLOBALS['lsjs4c_globals']['lsjs4c_debugMode'] = $layout->lsjs4c_debugMode;

        $GLOBALS['lsjs4c_globals']['lsjs4c_noMinifier'] = $layout->lsjs4c_noMinifier;

    }
}