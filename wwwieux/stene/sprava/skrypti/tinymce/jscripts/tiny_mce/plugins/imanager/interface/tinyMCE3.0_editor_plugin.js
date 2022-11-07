/**
 * $Id: editor_plugin_src.js 520 2008-01-07 16:30:32Z spocke $
 *
 * @author Moxiecode
 * @copyright Copyright  2004-2008, Moxiecode Systems AB, All rights reserved.
 */

(function() {
	tinymce.create('tinymce.plugins.IManagerPlugin', {
		init : function(ed, url) {

			// load common script
			tinymce.ScriptLoader.load(url + '/interface/common.js');
			
			// Register commands
			ed.addCommand('mceIManager', function() {
				var e = ed.selection.getNode();

				// Internal image object like a flash placeholder
				if (ed.dom.getAttrib(e, 'class').indexOf('mceItem') != -1)
					return;

				im.isMSIE  = tinymce.isIE;
				im.isGecko = tinymce.isGecko;
				im.oEditor = ed; 
				im.editor  = ed;
				im.selectedElement = e;					
				im.baseURL = url + '/imanager.php';	
				iManager_open();
			});

			// Register buttons
			ed.addButton('imanager', {
				title : 'iManager',
				cmd : 	'mceIManager',
				image: 	url + '/interface/images/tinyMCE/imanager.gif'
			});
			
			// Add a node change handler, selects the button in the UI when a image is selected
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('imanager', n.nodeName == 'IMG');
			});
		},

		getInfo : function() {
			return {
				longname : 	'iManager',
				author : 	'net4visions.com',
				authorurl : 'http://net4visions.com',
				infourl : 	'http://net4visions.com',
				version : 	'1.2.8'
			};
		}
	});
	
	// Register plugin
	tinymce.PluginManager.add('imanager', tinymce.plugins.IManagerPlugin);
})();	