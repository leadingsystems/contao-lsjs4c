CREATE TABLE `tl_layout` (
	`lsjs4c_loadLsjs` char(1) NOT NULL default '',
	`lsjs4c_doNotLoadCss` char(1) NOT NULL default '',
	`lsjs4c_appToLoad` blob NULL,
	`lsjs4c_appCustomizationToLoad` blob NULL,
	`lsjs4c_coreCustomizationToLoad` blob NULL,
	`lsjs4c_modulesToExclude` blob NULL,
	`lsjs4c_debugMode` char(1) NOT NULL default '',
	`lsjs4c_noCache` char(1) NOT NULL default '',
	`lsjs4c_noMinifier` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
