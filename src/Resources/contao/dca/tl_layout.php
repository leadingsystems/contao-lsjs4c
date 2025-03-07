<?php

namespace LeadingSystems\Lsjs4c;

use Contao\Backend;
use Contao\DataContainer;
use Contao\System;
use Contao\CoreBundle\DataContainer\PaletteManipulator;
use Symfony\Component\Finder\Finder;

PaletteManipulator::create()
    ->addLegend('lsjs4c_legend', 'default', PaletteManipulator::POSITION_APPEND)
    ->addField(['lsjs4c_loadLsjs', 'lsjs4c_appCustomizationToLoad', 'lsjs4c_appCustomizationToLoadTextPath', 'lsjs4c_coreCustomizationToLoad', 'lsjs4c_coreCustomizationToLoadTextPath', 'myCheckboxField', 'lsjs4c_debugMode', 'lsjs4c_noMinifier'], 'lsjs4c_legend', PaletteManipulator::POSITION_APPEND)
    ->applyToPalette('default', 'tl_layout');

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_loadLsjs'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_loadLsjs'],
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class' => 'm12'),
    'sql'                     => "char(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_appCustomizationToLoad'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_appCustomizationToLoad'],
    'inputType'        => 'checkboxWizard',
    'options_callback' => [tl_layout::class, 'getCheckboxOptions_appCustomizationToLoad'],
    'eval'             => ['multiple' => true, 'sortable' => true],
    'sql'              => "blob NULL"
];

$GLOBALS['TL_DCA']['tl_layout']['fields']['lsjs4c_coreCustomizationToLoad'] = [
    'label'            => &$GLOBALS['TL_LANG']['tl_layout']['lsjs4c_coreCustomizationToLoad'],
    'inputType'        => 'checkboxWizard',
    'options_callback' => [tl_layout::class, 'getCheckboxOptions_coreCustomizationToLoad'],
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
    public function getCheckboxOptions_appCustomizationToLoad(DataContainer $dc){
        return self::getCheckboxOptions($dc, 'lsjs-app*');
    }

    public function getCheckboxOptions_coreCustomizationToLoad(DataContainer $dc){
        return self::getCheckboxOptions($dc, 'lsjs-core*');
    }

    public function getCheckboxOptions(DataContainer $dc, String $searchname)
    {
        $options = [];

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
                $options[$searchPath.'/'.$dir->getRelativePathname()] = $searchPath.'/'.$dir->getRelativePathname();
            }
        }

        return $options;
    }
}

