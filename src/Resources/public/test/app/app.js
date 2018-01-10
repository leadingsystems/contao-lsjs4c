/*
 * This is a basic lsjs app for testing purposes.
 *
 * How to use it:
 *
 * 1. Copy the app folder to /files
 * 2. Activate lsjs in the contao layout and select this app
 * 3. Add an element of your choide (e.g. a div) with the id "templateTest"
 * 4. open the page.
 *
 * If everything works as expected, you should see the template output of the lsjs module "templateTest"
 */

(function() {
	var classdef_app = {
		obj_config: {},

		obj_references: {},

		initialize: function() {
			this.start();
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