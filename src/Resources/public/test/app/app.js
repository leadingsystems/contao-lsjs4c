(function() {
	var classdef_app = {
		obj_config: {},

		obj_references: {},

		initialize: function() {
		},

		start: function() {
			/*
			 * TESTS ->
			 */

			 lsjs.__moduleHelpers.templateTest.start({
				 str_containerSelector: '#templateTest'
			 });

			// lsjs.__moduleHelpers.ajaxTest.start();

			/*
			 * <- TESTS
			 */
		}
	};

	var class_app = new Class(classdef_app);

	window.addEvent('domready', function() {
		lsjs.__appHelpers.merconisApp = new class_app();
	});
})();