<?php

namespace LeadingSystems\Lsjs4c;

use Contao\Backend;
use Contao\DataContainer;
use Contao\System;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Symfony\Component\Finder\Finder;
use Contao\Database;

PaletteManipulator::create()
    ->addLegend('lsjs4c_legend', 'default', PaletteManipulator::POSITION_APPEND)
    ->addField(['lsjs4c_loadLsjs', 'lsjs4c_appsToLoad', 'lsjs4c_appCustomizationsToLoad', 'lsjs4c_debugMode', 'lsjs4c_noMinifier'], 'lsjs4c_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_layout');

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_loadLsjs'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_loadLsjs'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class' => 'm12'),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appsToLoad'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appsToLoad'],
    'inputType'        => 'checkboxWizard',
    'options_callback' => [tl_layout::class, 'getCheckboxOptions_appCustomization'],
    'eval'             => ['multiple' => true, 'sortable' => true],
    'sql'              => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appCustomizationsToLoad'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appCustomizationsToLoad'],
    'inputType'        => 'checkboxWizard',
    'options_callback' => [tl_layout::class, 'getCheckboxOptions_coreCustomization'],
    'eval'             => ['multiple' => true, 'sortable' => true],
    'sql'              => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_debugMode'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_debugMode'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class' => 'm12'),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_noMinifier'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_noMinifier'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class' => 'm12'),
    'sql'                     => "char(1) NOT NULL default ''"
);


class tl_layout extends Backend
{
    public function getCheckboxOptions_appCustomization(DataContainer $dc){
        return self::getCheckboxOptions($dc, 'lsjs-app*', 'app');
    }

    public function getCheckboxOptions_coreCustomization(DataContainer $dc){
        return self::getCheckboxOptions($dc, 'lsjs-core*', 'core');
    }

    public function getCheckboxOptions(DataContainer $dc, string $searchname, string $type)
    {
        $arrOptions = [];

        // Root path for searching
        $projectDir = System::getContainer()->getParameter('kernel.project_dir');
        $searchPaths = [
            'files',  // Search path for files
            'vendor'  // Search path for vendor
        ];

        // Iterate over all search paths
        foreach ($searchPaths as $searchPath) {
            // Create a new instance of Finder for each search path
            $finder = new Finder();

            // Recursive search in subdirectories for folders that start with $searchname
            $finder->directories()->in($projectDir.'/'.$searchPath)->name($searchname)->depth('>= 1');

            // Iterate over all found directories and add them as checkbox option
            foreach ($finder as $dir) {
                $arrOptions[$searchPath.'/'.$dir->getRelativePathname()] = $searchPath.'/'.$dir->getRelativePathname();
            }
        }

        // Get the current value from the database here
        $objResult = Database::getInstance()->prepare("SELECT lsjs4c_appsToLoad, lsjs4c_appCustomizationsToLoad FROM tl_layout WHERE id=?")
            ->execute($dc->id);

        if ($objResult->numRows)
        {
            $currentValue = [];

            if($type == 'app'){
                $currentValue = unserialize($objResult->lsjs4c_appsToLoad);
            }
            if($type == 'core'){
                $currentValue = unserialize($objResult->lsjs4c_appCustomizationsToLoad);
            }

            if ($currentValue && is_array($currentValue)) {
                foreach ($currentValue as $value) {
                    // It the value is already in this array don't add it
                    $fullPath = $projectDir . '/' . $value;
                    if (!in_array($value, $arrOptions) && is_dir($fullPath)) {
                        $arrOptions[$value] = $value;
                    }
                }
            }

        }

        return $arrOptions;
    }
}

