CREATE TABLE `tl_layout` (
	`lsjs4c_loadLsjs` char(1) NOT NULL default '',
	`lsjs4c_appsToLoad` blob NULL,
	`lsjs4c_modulesToExclude` blob NULL,
	`lsjs4c_debugMode` char(1) NOT NULL default ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;