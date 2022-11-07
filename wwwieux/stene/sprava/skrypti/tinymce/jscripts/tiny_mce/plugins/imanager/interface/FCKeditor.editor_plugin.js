// ================================================
// PHP image manager - iManager 
// ================================================
// iManager - FCKeditor editor interface (IE & Gecko)
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
	// FCKeditor editor - open iManager
	var FCKimanager = function(name) {  
		this.Name = name;		
	}  
	// manage the plugins' button behavior  
	FCKimanager.prototype.GetState = function() {  
		return FCK_TRISTATE_OFF;  
	}  
	 
	FCKCommands.RegisterCommand('imanager', new FCKimanager('imanager')) ;  
	 
	// Create the toolbar button. 
	var oimanagerItem = new FCKToolbarButton( 'imanager', "imanager", null, null, false, true ) ; 
	oimanagerItem.IconPath = FCKConfig.PluginsPath + 'imanager/images/imanager.gif' ;
	FCKToolbarItems.RegisterItem( 'imanager', oimanagerItem ) ;   
	FCKimanager.prototype.Execute = function() {  
		iManager_click(FCK,null)	
	}
	//-------------------------------------------------------------------------
	// fckeditor editor - init iManager
	function iManager_click(editor) {
		im.isMSIE = (navigator.appName == 'Microsoft Internet Explorer');
		im.isGecko = navigator.userAgent.indexOf('Gecko') != -1;		
		//im.oEditor = document.getElementById('eEditorArea'); // for FCKeditor up to V 2.2
		im.oEditor = document.getElementById('xEditingArea').firstChild; // for FCKeditor V 2.3
		im.editor = editor;
		im.selectedElement = im.getSelectedElement();
		im.baseURL = FCKConfig.PluginsPath + 'imanager/imanager.php';
		iManager_open(); // starting iManager
	}
	//-------------------------------------------------------------------------
	// include common interface code
	var js  = document.createElement('script');
	js.type	= 'text/javascript';
	js.src  = FCKConfig.PluginsPath + 'imanager/interface/common.js';
	// Add the new object to the HEAD element.
	document.getElementsByTagName('head')[0].appendChild(js) ; 
	//-------------------------------------------------------------------------