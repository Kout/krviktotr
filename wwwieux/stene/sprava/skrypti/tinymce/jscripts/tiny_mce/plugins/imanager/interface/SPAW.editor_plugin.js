// ================================================
// PHP image manager - iManager 
// ================================================
// iManager - SPAW editor interface (IE & Gecko)
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: LGPL - see license.txt
// (c)2005 All rights reserved.
// File: editor_plugin.js
// ================================================
// Revision: 1.0                   Date: 08/09/2005
// ================================================

	//-------------------------------------------------------------------------
	// SPAW editor - init iManager
	function SPAW_imanager_click(editor) {
		im.isMSIE = (navigator.appName == 'Microsoft Internet Explorer');
		im.isGecko = navigator.userAgent.indexOf('Gecko') != -1;
		im.oEditor = document.getElementById(editor + '_rEdit');  // set editor object
		im.editor = editor;		
		im.selectedElement = im.getSelectedElement();
		im.baseURL = '<?php echo $spaw_dir; ?>/plugins/imanager/imanager.php'; // plugin URL
		iManager_open(); // starting iManager
	}
	//-------------------------------------------------------------------------
	// include common interface code
	var js  = document.createElement('script');
	js.type	= 'text/javascript';
	js.src  = '<?php echo $spaw_dir; ?>/plugins/imanager/interface/common.js';
	// Add the new object to the HEAD element.
	document.getElementsByTagName('head')[0].appendChild(js) ; 
	//-------------------------------------------------------------------------