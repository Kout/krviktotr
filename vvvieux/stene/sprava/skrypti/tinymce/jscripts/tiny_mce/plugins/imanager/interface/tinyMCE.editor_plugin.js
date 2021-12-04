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
// Revision: 1.0                   Date: 12/31/2005
// ================================================

	//-------------------------------------------------------------------------
	// tinyMCE editor - imanager info
	function TinyMCE_ibrowser_getInfo() {
		return {
			longname  : 'iManager',
			author    : 'net4visions.com',
			authorurl : 'http://net4visions.com',
			infourl   : 'http://net4visions.com',
			version   : '1.2.2'
		};
	};

	// tinyMCE editor - open iManager
	function TinyMCE_imanager_getControlHTML(control_name) {
		switch (control_name) {
			case 'imanager':
				return '<img id="{$editor_id}_imanager" src="{$pluginurl}/images/imanager.gif" title="iManager" width="20" height="20" class="mceButtonNormal" onmouseover="tinyMCE.switchClass(this,\'mceButtonOver\');" onmouseout="tinyMCE.restoreClass(this);" onmousedown="tinyMCE.restoreAndSwitchClass(this,\'mceButtonDown\');" onclick="(iManager_click(\'{$editor_id}\'));">';
			}	
		return '';
	}
	//-------------------------------------------------------------------------
	// tinyMCE editor - init iManager
	function iManager_click(editor) {
		im.isMSIE = (navigator.appName == 'Microsoft Internet Explorer');
		im.isGecko = navigator.userAgent.indexOf('Gecko') != -1;
		im.oEditor = tinyMCE.getInstanceById(editor);
		im.editor = editor;		
		im.selectedElement = im.getSelectedElement();
		im.baseURL = tinyMCE.baseURL + '/plugins/imanager/imanager.php';
		iManager_open(); // starting iManager
	}
	//-------------------------------------------------------------------------
	// include common interface code
	var js  = document.createElement('script');
	js.type	= 'text/javascript';
	js.src  = tinyMCE.baseURL + '/plugins/imanager/interface/common.js';
	// Add the new object to the HEAD element.
	document.getElementsByTagName('head')[0].appendChild(js) ; 
	//-------------------------------------------------------------------------	