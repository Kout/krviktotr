// ================================================
// PHP image manager - iManager 
// ================================================
// iManager - tinyMCE editor interface (IE & Gecko)
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: LGPL - see license.txt
// (c)2005 All rights reserved.
// File: editor_plugin.js
// ================================================
// Revision: 1.0                   Date: 05/03/2006
// ================================================

	/* Import plugin specific language pack */
	tinyMCE.importPluginLanguagePack('imanager', 'en,de');
	
	//-------------------------------------------------------------------------
	var TinyMCE_imanagerPlugin = {
		getInfo: function() {			
			return {
				longname  : 'iManager',
				author    : 'net4visions.com',
				authorurl : 'http://net4visions.com',
				infourl   : 'http://net4visions.com',
				version   : '1.2.3'
			};
		},
		
		getControlHTML: function(cn) {
			switch (cn) {
				case 'imanager':
					return tinyMCE.getButtonHTML(cn, 'lang_imanager_desc', '{$pluginurl}/images/imanager.gif', 'mceImanager');
			}	
			return '';
		},
		
		execCommand: function(editor_id, element, command, user_interface, value) {
			switch (command) {
				case 'mceImanager':
					im.isMSIE  = (navigator.appName == 'Microsoft Internet Explorer');
					im.isGecko = navigator.userAgent.indexOf('Gecko') != -1;
					im.oEditor = tinyMCE.getInstanceById(editor_id);
					im.editor  = im.oEditor;
					im.selectedElement = im.oEditor.getFocusElement();					
					im.baseURL = tinyMCE.baseURL + '/plugins/imanager/imanager.php';	
					
					iManager_open(); // starting iManager
					return true;
			}
			return false;
		},
		
		handleNodeChange: function(editor_id, node, undo_index, undo_levels, visual_aid, any_selection) {
			if (node == null)
				return;
	
			do {
				if (node.nodeName == "IMG" && tinyMCE.getAttrib(node, 'class').indexOf('mceItem') == -1) {
					tinyMCE.switchClass(editor_id + '_imanager', 'mceButtonSelected');
					return true;
				}
			} while ((node = node.parentNode));
	
			tinyMCE.switchClass(editor_id + '_imanager', 'mceButtonNormal');
	
			return true;
		}
	};
	
	//-------------------------------------------------------------------------
	// include common interface code
	var js  = document.createElement('script');
	js.type	= 'text/javascript';
	js.src  = tinyMCE.baseURL + '/plugins/imanager/interface/common.js';	
	// Add the new object to the HEAD element.
	document.getElementsByTagName('head')[0].appendChild(js);	
	//-------------------------------------------------------------------------	
	
	tinyMCE.addPlugin('imanager', TinyMCE_imanagerPlugin);