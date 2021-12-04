// ================================================
// PHP image manager - iManager 
// ================================================
// iManager - Xinha editor interface (IE & Gecko)
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: LGPL - see license.txt
// (c)2005 All rights reserved.
// File: imanager.js
// ================================================
// Revision: 1.0                   Date: 08/09/2005
// ================================================

	//-------------------------------------------------------------------------
	// Xinha editor - open iManager
	function imanager(editor) {
		this.editor = editor;
		var cfg = editor.config;
		var self = this;	
		// register the toolbar buttons provided by this plugin
		cfg.registerButton({
		id       : "imanager",
		tooltip  : 'iManager',
		image    : _editor_url + '/plugins/imanager/images/imanager.gif',
		textMode : false,
		action   : function(editor) {
				   iManager_click(editor);
			}
		})
		cfg.addToolbarElement("imanager", "inserthorizontalrule", 1);
	};
	
	imanager._pluginInfo = {
		name          : "imanager",
	  	version       : "1.2",
	  	developer     : "Marco M. Jaeger",
	  	developer_url : "http://net4visions.com/",  
	  	license       : "LGPL"
	};
	//-------------------------------------------------------------------------
	// Xinha editor - init iManager
	function iManager_click(editor) {
		im.isMSIE = (navigator.appName == 'Microsoft Internet Explorer');
		im.isGecko = navigator.userAgent.indexOf('Gecko') != -1;
		im.oEditor = editor._iframe;
		im.editor = editor;		
		im.selectedElement = im.getSelectedElement();
		im.baseURL = _editor_url  + '/plugins/imanager/imanager.php';
		iManager_open(); // starting iManager
	}
	//-------------------------------------------------------------------------
	// include common interface code
	var js  = document.createElement('script');
	js.type	= 'text/javascript';
	js.src  = _editor_url  + '/plugins/imanager/interface/common.js';
	// Add the new object to the HEAD element.
	document.getElementsByTagName('head')[0].appendChild(js) ; 
	//-------------------------------------------------------------------------