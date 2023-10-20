CREATE TABLE `tl_layout` (
	`lsjs4c_loadLsjs` char(1) NOT NULL default '',
	`lsjs4c_appToLoad` blob NULL,
	`lsjs4c_appToLoadTextPath` text NULL,
	`lsjs4c_appCustomizationToLoad` blob NULL,
	`lsjs4c_appCustomizationToLoadTextPath` text NULL,
	`lsjs4c_coreCustomizationToLoad` blob NULL,
	`lsjs4c_coreCustomizationToLoadTextPath` text NULL,
	`lsjs4c_debugMode` char(1) NOT NULL default '',
	`lsjs4c_noMinifier` char(1) NOT NULL default ''
) ENGINE=InnoDB DEFAULT;
