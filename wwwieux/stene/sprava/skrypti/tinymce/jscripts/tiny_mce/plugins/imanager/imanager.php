<?php
	// ================================================
	// PHP image manager - iManager 
	// ================================================
	// iManager dialog
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: LGPL - see license.txt
	// File: imanager.php
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.2                   Date: 05/03/2006
	// ================================================
	
	//-------------------------------------------------------------------------
	// unset $cfg['ilibs_incl'] - dynamic image library
	if (isset($cfg['ilibs_inc'])) {
		$cfg['ilibs_inc'] = '';
	}
	//-------------------------------------------------------------------------
	// include configuration settings
	include dirname(__FILE__) . '/config/config.inc.php';
	include dirname(__FILE__) . '/langs/lang.class.php';	
	//-------------------------------------------------------------------------
	// language settings	
	$l = (isset($_REQUEST['lang']) ? new PLUG_Lang($_REQUEST['lang']) : new PLUG_Lang($cfg['lang']));
	$l->setBlock('imanager');	
	//-------------------------------------------------------------------------
	// if set, include file specified in $cfg['ilibs_incl']; hardcoded libraries will be ignored!	
	if (!empty($cfg['ilibs_inc'])) {
		include $cfg['ilibs_inc'];
	}	
	//-------------------------------------------------------------------------		
	// set current image library
	$clib = (isset($_REQUEST['clib']) ? $_REQUEST['clib'] : '');
	//-------------------------------------------------------------------------	
	$value_found = false;
	// callback function for preventing listing of non-library directory
	function is_array_value($value, $key, $tlib) {
		global $value_found;
		if (is_array($value)) {
			array_walk($value, 'is_array_value', $tlib);
		} if ($value == $tlib) {
			$value_found = true;
		}
	}	
	//-------------------------------------------------------------------------	
	array_walk($cfg['ilibs'], 'is_array_value', $clib);	
	if (!$value_found || empty($clib)) {
		$clib = $cfg['ilibs'][0]['value'];
	}		
	//-------------------------------------------------------------------------
	// create library dropdown
	$lib_options = liboptions($cfg['ilibs'], '', $clib, '');	
	//-------------------------------------------------------------------------	
	// run mode - e.g. ?mode=1 for plugin or ?mode=2 for standalone	
	$mode = (isset($_REQUEST['mode']) ? $_REQUEST['mode'] : $cfg['mode']);	
	//-------------------------------------------------------------------------	
?>
<!-- do not delete this line - it's need for proper working of the resizeDialogToContent() function -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<head>
<title><?php echo $l->m('im_002'); ?></title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $l->getCharset(); ?>">
<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" />
<style type="text/css">
<!--
@import url("css/style.css");
-->
</style>
<script language="javascript" type="text/javascript" src="scripts/resizeDialog.js"></script>
<script language="JavaScript" type="text/javascript" src="scripts/validateForm.js"></script>
<script language="JavaScript" type="text/JavaScript">
<!--
// ============================================================
// = global instance V 1.0, date: 04/07/2005                  =
// ============================================================
	function iManager() {
		// browser check
		this.isMSIE  = (navigator.appName == 'Microsoft Internet Explorer');
		this.isGecko = (navigator.userAgent.indexOf('Gecko') != -1);		
		// color values
		this.gcfld   = null;
		this.gifld   = null;			
	};	
	var iManager = new iManager();
// ============================================================
// = insertImage, date: 08/06/2005                            =
// ============================================================
	function insertImage() {
		var formObj = document.forms[0];
		var args = {};
		//-------------------------------------------------------------------------		
		if ('<?php echo $mode; ?>' == 2) { // standalone mode - image will be saved to local file										
			if (formObj.pr_src.value == '') { // no valid picture has been selected				
				alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
				return;
			}
			updatePreview(2);
			return;  
		}
		//-------------------------------------------------------------------------
		// determine active menu
		var x = document.getElementById('menuBarDiv').getElementsByTagName('li');
		for (var i = 0; i < x.length; i++) {
			if (x[i].className == 'btnDown') {
				if (x[i].id == 'mbtn_po') { // popup mode
					if(formObj.chkP.checked) {								
						args.action = 2; // delete popup link
					} else { // create / edit link to popup image
						args.action    = 1; 
						args.popUrl    = '<?php echo $cfg['pop_url']; ?>'; // link to popup.php						
						args.popSrc    = (formObj.popSrc.value)   ? (formObj.popSrc.value)   : '';						
						args.popTitle  = (formObj.popTitle.value) ? (formObj.popTitle.value) : '';
						args.popTxt    = '<?php echo $l->m('in_036'); ?>';
						if (formObj.popClassName.selectedIndex > 0) { // if class style is selected
							args.popClassName = (formObj.popClassName.options[formObj.popClassName.selectedIndex].value) ? (formObj.popClassName.options[formObj.popClassName.selectedIndex].value) : '';
						}
						args.caption = formObj.pr_chkCaption.checked ? formObj.pr_chkCaption.value : '';  																		
					}								
				}
			}
		}
		//-------------------------------------------------------------------------
		// check if valid image is selected
		if (!args.action) { // if not popup	mode, check whether there is a valid image selected		
			if (formObj.pr_src.value == '') { // no valid picture has been selected				
				alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
				return;
			}
			//-------------------------------------------------------------------------
			// destination check
			if (formObj.chk_oFile.checked == false) { 		// render image to dynamic thumbnail					
				args.src    = updatePreview(1);											
				args.width  = (formObj.rs_width.value)  ? (formObj.rs_width.value)  : '';
				args.height = (formObj.rs_height.value) ? (formObj.rs_height.value) : '';					
			} else if (formObj.chk_oFile.checked == true) { // render image to file						
				args.src 	= (formObj.pr_src.value)    ? (formObj.pr_src.value)    : '';									
				args.width  = (formObj.pr_width.value)  ? (formObj.pr_width.value)  : '';
				args.height = (formObj.pr_height.value) ? (formObj.pr_height.value) : '';					
			}
			//-------------------------------------------------------------------------
			if ('<?php echo $cfg['furl']; ?>' == true) { // create full url incl. e.g. http://localhost....
				args.src = '<?php echo $cfg['base_url']; ?>' + args.src;
			}
						
			args.align 	= (formObj.pr_align.value)  ? (formObj.pr_align.value)  : '';
			args.border	= (formObj.pr_border.value) ? (formObj.pr_border.value) : '';				
			args.alt 	= (formObj.pr_alt.value)    ? (formObj.pr_alt.value)    : '';
			args.title 	= (formObj.pr_title.value)  ? (formObj.pr_title.value)  : '';
			args.hspace = (formObj.pr_hspace.value) ? (formObj.pr_hspace.value) : '';
			args.vspace = (formObj.pr_vspace.value) ? (formObj.pr_vspace.value) : '';
			if (formObj.pr_class.selectedIndex > 0) {
				args.className = (formObj.pr_class.options[formObj.pr_class.selectedIndex].value) ? (formObj.pr_class.options[formObj.pr_class.selectedIndex].value) : '';
			}
			// caption parameters
			args.caption = formObj.pr_chkCaption.checked ? formObj.pr_chkCaption.value : '';
			args.captionClass = (formObj.pr_captionClass.options[formObj.pr_captionClass.selectedIndex].value) ? (formObj.pr_captionClass.options[formObj.pr_captionClass.selectedIndex].value) : '';
		} else { // check whether there is valid popup image
			if (formObj.popSrc.value == '') { // no valid picture has been selected				
				alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
				return;
			}
		}		
						
		//-------------------------------------------------------------------------	
		// save image to wysiwyg editor and close window
		window.returnValue = args;
		window.close();				
		
		if (iManager.isGecko) { // Gecko				
			<?php					
				if (!empty($_GET['callback'])) {          				
					echo "opener." . $_GET['callback'] . "('" . $_GET['editor']. "',this);\n";
				};
			?>	
		}	
	} 
// ============================================================
// = imanager init V 1.0, date: 12/03/2004                    =
// ============================================================
	function init() {		
		var formObj = document.forms[0];		
		btnInit('menuBarDiv', 'subMenuBarDiv', 'wzMenuBarDiv');	 // init menu buttons		
		document.getElementById('mainDivHeader').innerHTML = setTitle('imDiv'); 

		//-------------------------------------------------------------------------			
		// hide library selection if there is only one library available!
		if (formObj.ilibs.options.length > 1) {
			changeClass(0,'ilibsDiv','showit');
		}
		
		//-------------------------------------------------------------------------
		// window arguments			
		var args = window.dialogArguments;		
		if (args) {										// if dialog argument are available
			if (args.src) { 							// source is image and maybe also link				
				initImageArgs(); 						// init and set image attributes					
			} else if(args.a) { 						// source is popup image only
				setImagePopup(args.popSrc);				// update popup preview				
				formObj.popSrc.value = args.popSrc;		// popup image url
				formObj.popTitle.value = args.popTitle;	// link title
				for (var i = 0; i < formObj.popClassName.options.length; i++) {	// CLASS value
					if (formObj.popClassName.options[i].value == args.popClassName) {
						formObj.popClassName.options.selectedIndex = i;				
					}
				}		
			}
		}		 
		
		//-------------------------------------------------------------------------		
		preloadImages('images/firefox.gif','images/explorer.gif','images/img_in.gif','images/img_at.gif','images/img_tb.gif','images/img_po.gif','images/img_res.gif','images/img_crop.gif','images/img_flip.gif','images/img_col.gif','images/img_wm.gif','images/img_ovl.gif','images/img_mask.gif','images/img_wiz.gif','images/img_inf.gif','images/help.gif','images/help_off.gif','images/about.gif','images/about_off.gif','images/im.gif','images/infa_off.gif','images/infa.gif','images/infi_off.gif','images/infi.gif','images/osec_off.gif','images/osec.gif','images/csec_off.gif','images/csec.gif','images/close_off.gif','images/close.gif','images/crop_off.gif','images/crop.gif','images/reload_off.gif','images/reload.gif','images/ocrop_off.gif','images/ocrop.gif','images/ccrop_off.gif','images/ccrop.gif','images/prev_off.gif','images/prev.gif','images/symbols_off.gif','images/symbols.gif','images/dirview_off.gif','images/dirview.gif'); // preload images				
		
		// update preview for overlay images - default = overlay images
		var olib = absPath(formObj.ov_ilibs.options[formObj.ov_ilibs.selectedIndex].value);		
		ov_ilibsClick(olib,'');		
				
		setchkBox('chkResize','chkResize');
		updateStyle();			
		btnStage();
		resizeDialogToContent();
		window.focus();		
	}
// ============================================================
// = image buttons init V 1.0, date: 05/27/2005               =
// ============================================================
	function btnInit() {
		// <menuBarDiv>, <subMenuBarDiv>, <wzMenuBarDiv>
		var args = btnInit.arguments;		
		for (var k = 0; k < args.length; k++) {			
			var  x = document.getElementById(args[k]).getElementsByTagName('li');
			for (var i = 0; i < x.length; i++) {
				if (x[i].className   == 'btnUp') {
					x[i].onmouseover = btn_over;
					x[i].onmouseout  = btn_out;
					x[i].onmousedown = btn_down;
					x[i].onclick     = btn_click;
				}
			}
		}
	}
// ============================================================
// = menu buttons V 1.0, date: 06/03/2005                     =
// ============================================================	
	function btn_over() {	// menu button hover
		if (this.className != 'btnDown') {
			this.className  = 'btnOver';
		}
	}	
	function btn_out() {	// menu button out
		if (this.className != 'btnDown') {
			this.className  = 'btnUp';
		}
	}
	function btn_down() {	// menu button down
		if (this.className != 'btnDown') {
			this.className  = 'btnDown';
		}
	}
	function btn_click() {	// menu button click		
		var formObj = document.forms[0];
		// called from wizard settings section
		var args = btn_click.arguments;
		if(document.getElementById(args[0]) != null) { 		
			obj     = document.getElementById(args[0]);			
			this.id = document.getElementById(args[1]).id;			
		// normal call
		} else {
			obj = this;
			while (obj.nodeName != 'HTML') {
				obj = obj.parentNode;
				if (obj.id == 'menuBarDiv' || obj.id == 'subMenuBarDiv' || obj.id == 'wzMenuBarDiv') {				
					break
				}
			}
		}

		var x = document.getElementById(obj.id).getElementsByTagName('li');
		for (var i = 0; i < x.length; i++) {
			if (x[i].className == 'btnDown') {				
				if (x[i].id != this.id) {					
					x[i].className = 'btnUp';
				}
			}
		}	
		//-------------------------------------------------------------------------	
		if (obj.id == 'menuBarDiv') {			// main menu action
			// if standalone mode, image properties and image popup not available
			if ('<?php echo $mode; ?>' == 2) { // running in standalone mode
				if (this.id == 'mbtn_at' || this.id == 'mbtn_po') { // properties or toolbox functions				
					alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_037'); ?>');
					this.className = 'btnUp';
					return;
				}			
			} 
					
			// check whether image has been selected or not
			if (this.id == 'mbtn_at' || this.id == 'mbtn_tb') { // properties or toolbox functions
				if(!btnStage()) {
					alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
					this.className = 'btnUp';
					return;
				}
			}
			// reset all classes to "hideit"
			changeClass(0,'imDiv','hideit','inDiv','hideit','atDiv','hideit','tbDiv','hideit','ouDiv','showit','ciDiv','hideit','subMainDivWrap','hideit','raDiv','hideit');		
			// get element, set title			
			elm = this.id.substring(this.id.length-2, this.id.length);			
			elm = elm + 'Div';			
			document.getElementById('mainDivHeader').innerHTML = setTitle(elm);
			
			if (this.id == 'mbtn_po') {			
				var iProps = window.dialogArguments;			
				if (iProps && iProps.a) { // show remove link only if link			 
					changeClass(1,'fileDivWrap','hideit','fileDiv','hideit','img_ren','hideit','img_del','hideit','inDiv','showit','poDiv','showit','poDelDiv','showit','raDiv','hideit');
				} else {
					changeClass(1,'fileDivWrap','hideit','fileDiv','hideit','img_ren','hideit','img_del','hideit','inDiv','showit','poDiv','showit','poDelDiv','hideit','raDiv','hideit');
				}
			} else {						
				<?php if (($cfg['create'] && isset($cfg['ilibs_inc'])) || $cfg['upload'] || $cfg['rename'] || $cfg['delete']) { ?>
					changeClass(1,'poDiv','hideit','fileDivWrap','showit','img_ren','showit','img_del','showit',elm,'showit','raDiv','showit');
				<?php } else { ?>
					changeClass(1,'poDiv','hideit','fileDivWrap','hideit','img_ren','showit','img_del','showit',elm,'showit','raDiv','showit');
				<?php }; ?>
			}
			
			// if subMenu button is selected - get selected subMenu button and show div
			if (this.id == 'mbtn_tb') {
				var x = document.getElementById('subMenuBarDiv').getElementsByTagName('li');
				for (var i = 0; i < x.length; i++) {
					
					if (x[i].className == 'btnDown') {										
						elm = x[i].id.substring(x[i].id.length-2,x[i].id.length);			
						elm = elm + 'Div';								
						if (elm != 'ouDiv') { 	// show sub section
							changeClass(1,'subMainDivWrap','showit',elm,'showit');
						} else { 				// hide sub section
							changeClass(1,'subMainDivWrap','hideit');
						}					
					}
				}				
			}	
		//-------------------------------------------------------------------------	
		} else if (obj.id == 'subMenuBarDiv') { // sub menu action
			// set default value for watermark text
			if (document.forms[0].wm_text.value == '') { // set default value for watermark text
				var curdate = new Date();			
				document.forms[0].wm_text.value = String.fromCharCode(169) + ' Copyright, ' + curdate.getFullYear();
			}		

			elm = this.id.substring(this.id.length-2, this.id.length);			
			elm = elm + 'Div';			
			document.getElementById('subMainDivHeader').innerHTML = setTitle(elm);
			if (elm != 'ouDiv') {
				changeClass(1,'subMainDivWrap','showit','rsDiv','hideit','crDiv','hideit','orDiv','hideit','fiDiv','hideit','wmDiv','hideit','ovDiv','hideit','msDiv','hideit','wzDiv','hideit','seDiv','hideit','ciDiv','hideit',elm, 'showit');	
			} else {
				changeClass(1,'subMainDivWrap','hideit');	
			}
		//-------------------------------------------------------------------------
		} else if (obj.id == 'wzMenuBarDiv') { // wizard menu action					
			elm = this.id.substring(this.id.length-4, this.id.length);			
			x = elm + 'Div';								// id of wizard section e.g. wzbeDiv			
			y = x.substring(x.indexOf('wz')+2,x.length);	// id of wizard section details e.g. beDiv			
			document.getElementById('subMainDivHeader').innerHTML = setTitle(x);			
			if (x != 'wzslDiv') {				
				changeClass(1,'wzbeDiv','hideit','wzfrDiv','hideit','wzshDiv','hideit','wzbrDiv','hideit','wzrcDiv','hideit','wzelDiv','hideit', x, 'showit', y, 'showit');	
			} else {
				changeClass(1,'beDiv','hideit','frDiv','hideit','shDiv','hideit','brDiv','hideit','rcDiv','hideit','elDiv','hideit');	
				changeClass(1,'wzslDiv','showit','wzbeDiv','showit','wzfrDiv','showit','wzshDiv','showit','wzbrDiv','showit','wzrcDiv','showit','wzelDiv','showit');	
			}
		}
		resizeDialogToContent();
	}
// ============================================================
// = set title - V 1.0, date: 06/03/2005                      =
// ============================================================
	function setTitle(elm) {
		var retstr;
		switch(elm) {
			case 'imDiv':
				retstr = '<?php echo $l->m('im_004'); ?>';
				break;		
			case 'inDiv':
				retstr = '<?php echo $l->m('im_008'); ?>';
				break;
			case 'atDiv':
				retstr = '<?php echo $l->m('im_010'); ?>';
				break;
			case 'tbDiv':
				retstr = '<?php echo $l->m('im_012'); ?>';
				break;
			case 'poDiv':
				retstr = '<?php echo $l->m('im_014'); ?>';
				break;
			case 'rsDiv':
				retstr = '<?php echo $l->m('rs_002'); ?>';
				break;
			case 'crDiv':
				retstr = '<?php echo $l->m('cr_002'); ?>';
				break;
			case 'orDiv':
				retstr = '<?php echo $l->m('or_002'); ?>';
				break;
			case 'fiDiv':
				retstr = '<?php echo $l->m('co_002'); ?>';
				break;
			case 'wmDiv':
				retstr = '<?php echo $l->m('wm_002'); ?>';
				break;
			case 'ovDiv':
				retstr = '<?php echo $l->m('ov_002'); ?>';
				break;
			case 'msDiv':
				retstr = '<?php echo $l->m('ms_002'); ?>';
				break;
			case 'wzDiv':
				retstr = '<?php echo $l->m('wz_002'); ?>';
				break;
			case 'seDiv':
				retstr = '<?php echo $l->m('se_002'); ?>';
				break;
			case 'wzslDiv':
				retstr = '<?php echo $l->m('wz_002'); ?>';
				break;
			case 'wzbeDiv':
				retstr = '<?php echo $l->m('wz_002'); ?>' + ' - ' + '<?php echo $l->m('be_002'); ?>';
				break;
			case 'wzfrDiv':
				retstr = '<?php echo $l->m('wz_002'); ?>' + ' - ' + '<?php echo $l->m('fr_002'); ?>';
				break;
			case 'wzshDiv':
				retstr = '<?php echo $l->m('wz_002'); ?>' + ' - ' + '<?php echo $l->m('sh_002'); ?>';
				break;
			case 'wzbrDiv':
				retstr = '<?php echo $l->m('wz_002'); ?>' + ' - ' + '<?php echo $l->m('br_002'); ?>';
				break;
			case 'wzrcDiv':
				retstr = '<?php echo $l->m('wz_002'); ?>' + ' - ' + '<?php echo $l->m('rc_002'); ?>';
				break;
			case 'wzelDiv':
				retstr = '<?php echo $l->m('wz_002'); ?>' + ' - ' + '<?php echo $l->m('el_002'); ?>';
				break;
			default:
				retstr = '<?php echo $l->m('im_016'); ?>'; 		
		}
		return retstr;	
	}
// ============================================================
// = get image path and update ilist V 1.0, date: 04/25/2005  =
// ============================================================
	function initImageArgs() {
		var formObj = document.forms[0];		
		var args    = window.dialogArguments;
		
		// in case of full url, remove 'http://		
		var pos = args.src.indexOf('://');
		if (pos != -1) {			
   			pos = args.src.indexOf('/', pos + 3 ); // + length of '://'   			
			args.src = args.src.substring(pos);			
		}		
		
		// set current image file, and library
		var pos   = args.src.lastIndexOf('/');
		var cfile = args.src.slice(pos+1,args.src.length);			
		var clib  = absPath(args.src.slice(0,pos+1)); // relative path to library
				
		// set current directory/library & update image list
		for (var i = 0; i < formObj.ilibs.options.length; i++) {
			if(formObj.ilibs.options[i].value == clib) {
				formObj.ilibs.options.selectedIndex = i;	
				formObj.param.value = 'update' + '|' + cfile;			
				formObj.submit();						
			}
		}		
	}
// ============================================================
// = set image properties, date: 08/06/2005                   =
// ============================================================		
	function setImageArgs() {				
		var formObj = document.forms[0];		
		var args    = window.dialogArguments;						
		formObj.pr_width.value 	= args.width;						// WIDTH value		
		formObj.pr_height.value	= args.height;						// HEIGHT value
		formObj.pr_alt.value 	= args.alt;							// ALT text		
		formObj.pr_title.value 	= args.title;						// DESCR text
		formObj.pr_border.value = args.border ? args.border : '';	// BORDER value	
		formObj.pr_vspace.value = args.vspace ? args.vspace : '';	// VSPACE value				
		formObj.pr_hspace.value = args.hspace ? args.hspace : '';	// HSPACE value		
		formObj.pr_width.value 	= args.width; 						// WIDTH value
		formObj.pr_height.value	= args.height; 						// HEIGHT value
		for (var i = 0; i < formObj.pr_align.options.length; i++) {	// ALIGN value 
			if (formObj.pr_align.options[i].value == args.align) {
				formObj.pr_align.options.selectedIndex = i;				
			}
		}		
		for (var i = 0; i < formObj.pr_class.options.length; i++) {	// CLASS value
			if (formObj.pr_class.options[i].value == args.className) {
				formObj.pr_class.options.selectedIndex = i;				
			}
		}
		
		if (args.caption == 1) { // if image caption
			formObj.pr_chkCaption.checked = true;
			for (var i = 0; i < formObj.pr_captionClass.options.length; i++) {	// CLASS value
				if (formObj.pr_captionClass.options[i].value == args.captionClass) {
					formObj.pr_captionClass.options.selectedIndex = i;				
				}
			}
		}
		
		//-------------------------------------------------------------------------
		// set resize values
		formObj.rs_width.value 	= args.width;
		formObj.rs_height.value = args.height;
		//-------------------------------------------------------------------------
		// set crop values (50% of source)
		formObj.cr_width.value 	= Math.round(args.width / 2);
		formObj.cr_height.value = Math.round(args.height / 2);	
		
		//-------------------------------------------------------------------------
		// set popup preview in case it's a popup		
		if (args.popSrc) {						
			setImagePopup(args.popSrc);				// update popup preview				
			formObj.popTitle.value = args.popTitle;	// link title
			for (var i = 0; i < formObj.popClassName.options.length; i++) {	// CLASS value
				if (formObj.popClassName.options[i].value == args.popClassName) {
					formObj.popClassName.options.selectedIndex = i;				
				}
			}		
		}
		
		//-------------------------------------------------------------------------
		// set toolbox settings if it's a dynamic thumbnail
		if (args.tset ) { 
			setTbox(unescape(args.tset), args.height);
			updatePreview(0);	
		}
			
		//-------------------------------------------------------------------------
		// resetting param value 
		formObj.param.value = ''; 				
	}

// ============================================================
// = set toolbox values V 1.0, date: 01/28/2005               =
// ============================================================
	 function setTbox(string, height) {		
		var formObj = document.forms[0];		
		var obj = document.getElementById('chk_oFile');
		obj.checked = false;
		oFileChange(obj); // show/hide quality on format change
		
		var separator = '&';
		var stringArray = string.split(separator);		
		for (var s = 0; s < stringArray.length; s++) { // toolbox settings  			
			var separator = '=';
			var tsetArray = stringArray[s].split(separator);				
			switch (tsetArray[0]) { // toolbox switch   			
				//------------------------------------------------------------------------- DIMENSIONS
				case 'w': // width				
					formObj.chkResize.checked = true;
					formObj.rs_width.value = tsetArray[1];
					formObj.rs_height.value = height; // set height in case it's not set later
					setchkBox('chkResize','chkResize');
					break;
				case 'h': // height				
					formObj.rs_height.value = tsetArray[1];
					break;
				//------------------------------------------------------------------------- QUALITY (JPEG)
				case 'q': // quality - if jpeg				
					for (var i = 0; i < formObj.sel_oQuality.options.length; i++) { 
						if (formObj.sel_oQuality.options[i].value == tsetArray[1]) {						
							formObj.sel_oQuality.options.selectedIndex = i;								
						}
					}	
					break;
				//------------------------------------------------------------------------- FORMAT
				case 'f': // image format
					changeClass(0,'oQualityDiv','hideit');							
					var iValue = imageValue(tsetArray[1]);
					// set file format
					formObj.sel_oFormat.options.selectedIndex = 0; // set default = jpeg
					for (var i = 0; i < formObj.sel_oFormat.options.length; i++) {			
						if (formObj.sel_oFormat.options[i].value == iValue) {						
							formObj.sel_oFormat.options.selectedIndex = i;
							var obj = formObj.sel_oFormat.options[i];
							oFormatChange(obj);								
						}
					}	
					break;
				//------------------------------------------------------------------------- BACKGROUND COLOR
				case 'bg':				
					formObj.or0_col.value = '#' + tsetArray[1];
					formObj.or0_icol.style.backgroundColor = '#' + tsetArray[1];				
					synColor('or0_col');				
					break;
				//------------------------------------------------------------------------- RESIZE IMAGE				
				case 'iar': // ignore aspect ratio				
					formObj.rs_type[1].checked = true;
					break;			
				case 'far': // force aspect ratio				
					formObj.rs_type[2].checked = true;
					break;
				case 'zc': // zoom crop				
					formObj.rs_type[3].checked = true;
					break;
				case 'aoe': // allow enlargement				
					formObj.rs_chkEnla.checked = true;
					break;
				//------------------------------------------------------------------------- CROP IMAGE				
				case 'sx': // left				
					formObj.chkCrop.checked = true;
					formObj.cr_left.value = tsetArray[1];
					setchkBox('chkCrop','chkCrop');
					break;
				case 'sy': // top				
					formObj.cr_top.value = tsetArray[1];
					break;			
				case 'sw': // width				
					formObj.cr_width.value = tsetArray[1];
					break;
				case 'sh': // height				
					formObj.cr_height.value = tsetArray[1];
					break;
				//------------------------------------------------------------------------- ROTATE BY ANGLE				
				case 'ra':					
					formObj.chkRotate.checked = true;
					formObj.ro_type[0].checked = true;				
					for (var i = 0; i < formObj.ro_angle.options.length; i++) { 
						if (formObj.ro_angle.options[i].value == tsetArray[1]) {
							formObj.ro_angle.options.selectedIndex = i;				
						}
					}
					setchkBox('chkOrientation','chkRotate');	
					break;
				//------------------------------------------------------------------------- AUTO ROTATE				
				case 'ar': 				
					formObj.chkRotate.checked = true;
					formObj.ro_type[1].checked = true;				
					for (var i = 0; i < formObj.ro_auto.options.length; i++) { 
						if (formObj.ro_auto.options[i].value == tsetArray[1]) {
							formObj.ro_auto.options.selectedIndex = i;				
						}
					}
					setchkBox('chkOrientation','chkRotate');	
					break;								
				//------------------------------------------------------------------------- FILTER SETTINGS	
				case 'fltr[]': // filter settings				
					var separator = '|';
					var fltrArray = tsetArray[1].split(separator);				
					switch(fltrArray[0]) { // filter switch
						//-------------------------------------------------------------------------	FLIP IMAGE						
						case 'flip':					
							// &fltr[]=flip|<value>					
							formObj.chkFlip.checked = true;							
							for (var i = 0; i < formObj.or_flip.options.length; i++) { 
								if (formObj.or_flip.options[i].value == fltrArray[1]) {
									formObj.or_flip.options.selectedIndex = i;				
								}
							}
							setchkBox('chkOrientation','chkFlip');	
							break;
						//------------------------------------------------------------------------- FILTERS
						//------------------------------------------------------------------------- EFFECTS									
						case 'neg': // negative					
							// &fltr[]=neg	
							formObj.chkEffects.checked = true;
							formObj.co_type[1].checked = true;				
							setchkBox('chkColorize','chkEffects');
							break;
						//------------------------------------------------------------------------- THRESHOLD
						case 'th': // threshold
						// &fltr[]=th|<value>					
							formObj.chkEffects.checked = true;
							formObj.co_type[2].checked = true;									
							for (var i = 0; i < formObj.tb_thres.options.length; i++) { 
								if (formObj.tb_thres.options[i].value == fltrArray[1]) {
									formObj.tb_thres.options.selectedIndex = i;				
								}
							}
							setchkBox('chkColorize','chkEffects');	
							break;
						//------------------------------------------------------------------------- SEPIA TONE
						case 'sep': // sepia
							// &fltr[]=sep|<value>|<color>					
							formObj.chkEffects.checked = true;
							formObj.co_type[3].checked = true;									
							for (var i = 0; i < formObj.se_in.options.length; i++) { 
								if (formObj.se_in.options[i].value == fltrArray[1]) {
									formObj.se_in.options.selectedIndex = i;				
								}
							}	
							formObj.se0_col.value  = '#' + fltrArray[2];
							formObj.se0_icol.style.backgroundColor = '#' + fltrArray[2];	
							setchkBox('chkColorize','chkEffects');
							break;
						//------------------------------------------------------------------------- COLORIZE
						case 'clr': // colorize
							// &fltr[]=clr|<value>|<color>					
							formObj.chkEffects.checked = true;
							formObj.co_type[4].checked = true;									
							for (var i = 0; i < formObj.co_in.options.length; i++) { 
								if (formObj.co_in.options[i].value == fltrArray[1]) {
									formObj.co_in.options.selectedIndex = i;				
								}
							}	
							formObj.co0_col.value  = '#' + fltrArray[2];
							formObj.co0_icol.style.backgroundColor = '#' + fltrArray[2];	
							setchkBox('chkColorize','chkEffects');
							break;
						//------------------------------------------------------------------------- TOUCHUP
						//------------------------------------------------------------------------- GAMMA						
						case 'gam': // gamma
							// &fltr[]=gam|<value>							
							formObj.chkTouchup.checked = true;
							formObj.chkGamma.checked = true;						
							for (var i = 0; i < formObj.co_ga.options.length; i++) { 
								if (formObj.co_ga.options[i].value == fltrArray[1]) {
									formObj.co_ga.options.selectedIndex = i;				
								}
							}
							setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB');
							setchkBox('chkColorize','chkTouchup');
							break;
						//------------------------------------------------------------------------- BLUR
						case 'blur': // blur
							// &fltr[]=blur|<radius>							
							formObj.chkTouchup.checked = true;
							formObj.chkBlur.checked = true;						
							for (var i = 0; i < formObj.co_bl.options.length; i++) { 
								if (formObj.co_bl.options[i].value == fltrArray[1]) {
									formObj.co_bl.options.selectedIndex = i;				
								}
							}
							setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB');
							setchkBox('chkColorize','chkTouchup');
							break;						
						//------------------------------------------------------------------------- DESATURATION
						case 'ds': // desaturation - if desaturation eq 100 - check grayscale!
							// &fltr[]=ds|<value>																				
							for (var i = 0; i < formObj.co_ds.options.length; i++) { 
								if (formObj.co_ds.options[i].value == fltrArray[1]) {
									formObj.co_ds.options.selectedIndex = i;				
									if (fltrArray[1] == 100) { // grayscale								
										formObj.chkEffects.checked = true;
										formObj.co_type[0].checked = true;
										setchkBox('chkColorize','chkEffects');
									} else {								
										formObj.chkTouchup.checked = true;
										formObj.chkSaturation.checked = true;
										setchkBox('chkColorize','chkTouchup');							
									}						
								}
							}
							setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB');
							setchkBox('chkColorize','chkTouchup');
							break;
						//------------------------------------------------------------------------- UNSHARP MASK
						case 'usm':	// unsharp mask
							// &fltr[]=usm|<amount>|<radius>|<threshold>									
							formObj.chkTouchup.checked = true;
							formObj.chkUnsharp.checked = true;						
							for (var i = 0; i < formObj.wz_usa.options.length; i++) { 
								if (formObj.wz_usa.options[i].value == fltrArray[1]) {
									formObj.wz_usa.options.selectedIndex = i;				
								}
							}					
							for (var i = 0; i < formObj.wz_usr.options.length; i++) { 
								if (formObj.wz_usr.options[i].value == fltrArray[2]) {
									formObj.wz_usr.options.selectedIndex = i;				
								}
							}
							for (var i = 0; i < formObj.wz_ust.options.length; i++) { 
								if (formObj.wz_ust.options[i].value == fltrArray[3]) {
									formObj.wz_ust.options.selectedIndex = i;				
								}
							}
							setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB');
							setchkBox('chkColorize','chkTouchup');
							break;
						//------------------------------------------------------------------------- LEVEL
						case 'lvl': // level
							//&fltr[]=lvl|<channel>|<min>|<max>								
							formObj.chkTouchup.checked = true;
							formObj.chkLevel.checked = true;	
							for (var i = 0; i < formObj.wz_cha.options.length; i++) { 
								if (formObj.wz_cha.options[i].value == fltrArray[1]) {
									formObj.wz_cha.options.selectedIndex = i;				
								}
							}					
							for (var i = 0; i < formObj.wz_min.options.length; i++) { 
								if (formObj.wz_min.options[i].value == fltrArray[2]) {
									formObj.wz_min.options.selectedIndex = i;				
								}
							}
							for (var i = 0; i < formObj.wz_max.options.length; i++) { 
								if (formObj.wz_max.options[i].value == fltrArray[3]) {
									formObj.wz_max.options.selectedIndex = i;				
								}
							}
							setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB');
							setchkBox('chkColorize','chkTouchup');							
							break;							
						//------------------------------------------------------------------------- WHITE BALANCE
						case 'wb': // white balance
							//&fltr[]=wb|<c>]
							formObj.chkTouchup.checked = true;
							formObj.chkWhiteB.checked = true;	
							formObj.wb0_col.value = fltrArray[1];
							setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB');
							setchkBox('chkColorize','chkTouchup');							
							break;										
						//------------------------------------------------------------------------- WATERMARK TEXT						
						case 'wmt': // watermark text							
							// &fltr[]=wmt|1<text>|2<size>|3<align>|4<color>|5<font>|6<opacity>|7<margin>|8<angle>
							formObj.chkWatermark.checked = true;
							formObj.wm_type[0].checked = true;
							formObj.wm_text.value = fltrArray[1];
							for (var i = 0; i < formObj.wm_align.options.length; i++) { 
								if (formObj.wm_align.options[i].value == fltrArray[3]) {
									formObj.wm_align.options.selectedIndex = i;				
								}
							}
							for (var i = 0; i < formObj.wm_opacity.options.length; i++) { 
								if (formObj.wm_opacity.options[i].value == fltrArray[6]) {
									formObj.wm_opacity.options.selectedIndex = i;				
								}
							}
							for (var i = 0; i < formObj.wm_space.options.length; i++) { 
								if (formObj.wm_space.options[i].value == fltrArray[7]) {
									formObj.wm_space.options.selectedIndex = i;				
								}
							}
							if (fltrArray[5]) { // true type font						
								formObj.wmf_type[1].checked = true;
								for (var i = 0; i < formObj.wm_size.options.length; i++) {  
									if (formObj.wm_size.options[i].value == fltrArray[2]) {
										formObj.wm_size.options.selectedIndex = i;				
									}
								}
								for (var i = 0; i < formObj.wm_angle.options.length; i++) { 
									if (formObj.wm_angle.options[i].value == fltrArray[8]) {
										formObj.wm_angle.options.selectedIndex = i;				
									}
								}
								for (var i = 0; i < formObj.wm_font.options.length; i++) { 
									if (formObj.wm_font.options[i].value == fltrArray[5]) {
										formObj.wm_font.options.selectedIndex = i;				
									}
								}
								changeClass(0,'tb_wmts','hideit','tb_wmtt','showit');						
							} else { // system font					
								formObj.wmf_type[0].checked = true;
								for (var i = 0; i < formObj.wms_size.options.length; i++) { // system  font size 
									if (formObj.wms_size.options[i].value == fltrArray[2]) {
										formObj.wms_size.options.selectedIndex = i;				
									}
								}
								changeClass(0,'tb_wmts','showit','tb_wmtt','hideit');
							}							
							formObj.wm0_col.value = '#' + fltrArray[4];
							formObj.wm0_icol.style.backgroundColor = '#' + fltrArray[4];							
							changeClass(0,'tb_wmt','showit','wmPrevDiv','hideit');
							setchkBox('chkWatermark','chkWatermark');
							break;				
						//------------------------------------------------------------------------- WATERMARK IMAGE
						case 'wmi': // watermark image
							// &fltr[]=wmi|<file>|<align>|<opacity>|<margin>
							formObj.chkWatermark.checked = true;
							formObj.wm_type[1].checked = true;					
							for (var i = 0; i < formObj.wmf.length; i++) {
   								if (formObj.wmf[i].value == fltrArray[1]) {
      								formObj.wmf[i].checked = true;
      							}
   							}					
							for (var i = 0; i < formObj.wm_align.options.length; i++) { 
								if (formObj.wm_align.options[i].value == fltrArray[2]) {
									formObj.wm_align.options.selectedIndex = i;				
								}
							}
							for (var i = 0; i < formObj.wm_opacity.options.length; i++) { 
								if (formObj.wm_opacity.options[i].value == fltrArray[3]) {
									formObj.wm_opacity.options.selectedIndex = i;				
								}
							}
							for (var i = 0; i < formObj.wm_space.options.length; i++) { 
								if (formObj.wm_space.options[i].value == fltrArray[4]) {
									formObj.wm_space.options.selectedIndex = i;				

								}
							}
							changeClass(0,'wmPrevDiv','showit','tb_wmt','hideit');
							setchkBox('chkWatermark','chkWatermark');
							break;
						//------------------------------------------------------------------------- OVERLAY
						case 'over': // overlay
							//&fltr[]=over|<file>|<overlay/underlay>|<margin>|<opacity>		
							formObj.chkOverlay.checked = true;
							formObj.ovFile.value = fltrArray[1];						
							var clib = absPath(fltrArray[1].substring(0, fltrArray[1].lastIndexOf('/') +1));															
							ov_ilibsClick(clib, formObj.ovFile.value); // update olay library and select olay image
														
							for (var i = 0; i < formObj.ov_ilibs.options.length; i++) { 
								if (formObj.ov_ilibs.options[i].value == clib) {
									formObj.ov_ilibs.options.selectedIndex = i;				
								}
							}							
							for (var i = 0; i < formObj.ov_type.length; i++) {
								if (formObj.ov_type[i].value == fltrArray[2]) {
									formObj.ov_type[i].checked = true;
								}
   							}
							for (var i = 0; i < formObj.ov_space.options.length; i++) { 
								if (formObj.ov_space.options[i].value == fltrArray[3]) {
									formObj.ov_space.options.selectedIndex = i;				
								}
							}								
							for (var i = 0; i < formObj.ov_opacity.options.length; i++) { 
								if (formObj.ov_opacity.options[i].value == fltrArray[4]) {
									formObj.ov_opacity.options.selectedIndex = i;				
								}
							}							
							setchkBox('chkOverlay','chkOverlay');
							break;						
						//------------------------------------------------------------------------- IMAGE MASK						
						case 'mask': // image mask
							// &fltr[]=mask|<file>
							formObj.chkMask.checked = true;										
							for (var i = 0; i < formObj.msf.length; i++) {
   								if (formObj.msf[i].value == fltrArray[1]) {
      								formObj.msf[i].checked = true;
      							}
   							}
							setchkBox('chkMask','chkMask');			
							break;						
						//------------------------------------------------------------------------- WIZARD
						//------------------------------------------------------------------------- BEVEL						
						case 'bvl': // bevel					
							//&fltr[]=bvl|<width>|<color left & top>|<color right & bottom>					
							formObj.chkBevel.checked = true;
							for (var i = 0; i < formObj.wz_BevelWidth.options.length; i++) { 
								if (formObj.wz_BevelWidth.options[i].value == fltrArray[1]) {
									formObj.wz_BevelWidth.options.selectedIndex = i;				
								}
							}					
							formObj.be0_col.value = '#' + fltrArray[2];
							formObj.be1_col.value = '#' + fltrArray[3];
							formObj.be0_icol.style.backgroundColor = '#' + fltrArray[2];
							formObj.be1_icol.style.backgroundColor = '#' + fltrArray[3];											
							setchkBox('chkWizard','chkBevel');
							break;
						//------------------------------------------------------------------------- FRAME
						case 'fram': // frame
							//&fltr[]=fram|<width main border>|<width of each side>|<color main border>|<highlight bevel color>|<shadow color>					
							formObj.chkFrame.checked = true;
							for (var i = 0; i < formObj.fr_w.options.length; i++) { 
								if (formObj.fr_w.options[i].value == fltrArray[1]) {
									formObj.fr_w.options.selectedIndex = i;				
								}
							}		
							for (var i = 0; i < formObj.fr_wb.options.length; i++) { 
								if (formObj.fr_wb.options[i].value == fltrArray[2]) {
									formObj.fr_wb.options.selectedIndex = i;				
								}
							}					
							formObj.fr0_col.value = '#' + fltrArray[3];
							formObj.fr1_col.value = '#' + fltrArray[4];
							formObj.fr2_col.value = '#' + fltrArray[5];
							formObj.fr0_icol.style.backgroundColor = '#' + fltrArray[3];
							formObj.fr1_icol.style.backgroundColor = '#' + fltrArray[4];
							formObj.fr2_icol.style.backgroundColor = '#' + fltrArray[5];					
							setchkBox('chkWizard','chkFrame');
							break;
						//------------------------------------------------------------------------- DROP SHADOW
						case 'drop': // drop shadow
							//&fltr[]=drop|<distance>|<width>|<color>|<angle>|<fade>					
							formObj.chkShadow.checked = true;
							for (var i = 0; i < formObj.sh_margin.options.length; i++) { 
								if (formObj.sh_margin.options[i].value == fltrArray[1]) {
									formObj.sh_margin.options.selectedIndex = i;				
								}
							}
							for (var i = 0; i < formObj.wz_ShadowWidth.options.length; i++) { 
								if (formObj.wz_ShadowWidth.options[i].value == fltrArray[2]) {
									formObj.wz_ShadowWidth.options.selectedIndex = i;				
								}
							}					
							formObj.ds0_col.value = '#' + fltrArray[3];
							formObj.ds0_icol.style.backgroundColor = '#' + fltrArray[3];
							for (var i = 0; i < formObj.sh_angle.options.length; i++) { 
								if (formObj.sh_angle.options[i].value == fltrArray[4]) {
									formObj.sh_angle.options.selectedIndex = i;				
								}
							}					
							for (var i = 0; i < formObj.sh_fade.options.length; i++) { 
								if (formObj.sh_fade.options[i].value == fltrArray[5]) {
									formObj.sh_fade.options.selectedIndex = i;				
								}
							}
							setchkBox('chkWizard','chkShadow');						
							break;						
						//------------------------------------------------------------------------- ROUNDED BORDERS
						case 'bord': // rounded borders
							// &fltr[]=bord|<width>|<x radius>|<y radius>|<color>
							formObj.chkBorder.checked = true;
							for (var i = 0; i < formObj.brwidth.options.length; i++) { 
								if (formObj.brwidth.options[i].value == fltrArray[1]) {
									formObj.brwidth.options.selectedIndex = i;				
								}
							}									
							for (var i = 0; i < formObj.brxrad.options.length; i++) { 
								if (formObj.brxrad.options[i].value == fltrArray[2]) {
									formObj.brxrad.options.selectedIndex = i;				
								}
							}
							for (var i = 0; i < formObj.bryrad.options.length; i++) { 
								if (formObj.bryrad.options[i].value == fltrArray[3]) {
									formObj.bryrad.options.selectedIndex = i;				
								}
							}
							formObj.br1_col.value = '#' + fltrArray[1];
							formObj.br1_icol.style.backgroundColor = '#' + fltrArray[4];	
							setchkBox('chkWizard','chkBorder');
							break;
						//------------------------------------------------------------------------- ROUNDED IMAGE CORNERS
						case 'ric': // rounded image corners
							// &fltr[]=ric|<x radius>|<y radius>
							formObj.chkCorner.checked = true;														
							for (var i = 0; i < formObj.rcxrad.options.length; i++) { 
								if (formObj.rcxrad.options[i].value == fltrArray[1]) {
									formObj.rcxrad.options.selectedIndex = i;				
								}
							}
							for (var i = 0; i < formObj.bryrad.options.length; i++) { 
								if (formObj.rcyrad.options[i].value == fltrArray[2]) {
									formObj.rcyrad.options.selectedIndex = i;				
								}
							}						
							setchkBox('chkWizard','chkCorner');
							break;
						//------------------------------------------------------------------------- ELLIPSE
						case 'elip': // ellipse
							//&fltr[]=elip						
							formObj.chkEllipse.checked = true;
							setchkBox('chkWizard','chkEllipse');
							break;												
						default:
							alert('<?php echo $l->m('er_001').': '.$l->m('er_032'); ?> - ' + stringArray[s]);	
					} // end filter switch
				break; // case filter
				default:
      				alert('<?php echo $l->m('er_001').': '.$l->m('er_033'); ?> - ' + stringArray[s]);							
			} // end toolbox switch
		} // end toolbox settings (for)
		return;	
	}
// ============================================================
// = image change - set attributes V 1.0, date: 12/03/2004    =
// ============================================================
 	function imageChange() {		
		var formObj = document.forms[0];
		var args 	= imageChange.arguments;  												// image change arguments - set by rfiles.php						
		var clib    = absPath(formObj.ilibs.options[formObj.ilibs.selectedIndex].value);	// current library - absolute path		
		var cfile   = document.getElementById('cimg').attributes['cfile'].value;			// get current image
		var cwidth  = document.getElementById('cimg').attributes['cwidth'].value;			// get current width	
		var cheight = document.getElementById('cimg').attributes['cheight'].value;			// get current height			
		var csize   = document.getElementById('cimg').attributes['csize'].value.split('|');	// get current size (array)
		var ctype   = document.getElementById('cimg').attributes['ctype'].value.split('|');	// get current type (array)	
			
		//-------------------------------------------------------------------------
		// set default image attributes
		formObj.pr_src.value    = clib + cfile;		
		formObj.pr_width.value  = cwidth;
		formObj.pr_height.value = cheight;		
		formObj.pr_size.value   = csize[0];		
		formObj.pr_align.options.selectedIndex = 0;
		formObj.pr_class.options.selectedIndex = 0;
		document.getElementById('pr_sizeUnit').innerHTML = csize[1]; // e.g. kb		
		formObj.pr_alt.value 	= cfile.substr(0, cfile.length-4);
		formObj.pr_title.value 	= cfile.substr(0, cfile.length-4);		
		
		//-------------------------------------------------------------------------
		// update preview window	
		var sizes = resizePreview(cwidth, cheight, 150, 150);		
		var src = '<?php echo $cfg['scripts']; ?>' + 'phpThumb/phpThumb.php'; // command
		src = src + '?src=' + clib + cfile; // source file
		src = src + '&w=' + sizes['w']; // width		
		document.getElementById('inPrevFrame').src = src; // update regular preview
		document.getElementById('tbPrevFrame').src = src; // update toolbox preview
		
		//-------------------------------------------------------------------------
		// reset rename and delete info
		if ('<?php echo $cfg['rename']; ?>' == true) {
			formObj.in_srcnew.value  = cfile.substr(0, cfile.length-4); // default rename value			
		}
		if ('<?php echo $cfg['delete']; ?>' == true) {
			formObj.in_delinfo.value = cfile; 							// default delete value
		}
		//-------------------------------------------------------------------------
		// set toolbox values
		// set resize values
		formObj.rs_width.value 	= cwidth;
		formObj.rs_height.value = cheight;
		// set crop values
		formObj.cr_width.value 	= Math.round(cwidth/2);
		formObj.cr_height.value = Math.round(cheight/2);
		formObj.cr_top.value 	= 0;
		formObj.cr_left.value 	= 0;
		// update toolbox fields
		formObj.tb_info.value = '<?php echo $l->m('at_023'); ?>: ' + cwidth + 'px; ' + '<?php echo $l->m('at_024'); ?>: ' + cheight +'px; ' + '<?php echo $l->m('at_022'); ?>: ' + csize[0] + ' ' + csize[1] + ';';				
		formObj.tb_src.value  = cfile;		
		if ('<?php echo $mode; ?>' == 1) { 		// plugin mode
			//formObj.in_srcnew.value = cfile.substr(0, cfile.length-4);	
			formObj.chk_oFile.checked = true; 	// reset output destination to file
		}
		
		// update file format toolbox settings
		// set file format
		formObj.sel_oFormat.options.selectedIndex = 0; // set default = jpeg
		for (var i = 0; i < formObj.sel_oFormat.options.length; i++) {			
			if (formObj.sel_oFormat.options[i].value == ctype[0]) {						
				formObj.sel_oFormat.options.selectedIndex = i;
				var obj = formObj.sel_oFormat.options[i];
				oFormatChange(obj);								
			}
		}	
				
		//-------------------------------------------------------------------------
		// change image attributes in case it's an existing image		
		if (args[0] == 'update') { 	// if argument from rfiles.php received				
			setImageArgs(); 		// update image attributes
		} else if (args[0] == 'delete') { // image was deleted
			document.getElementById('cimg').attributes['cfile'].value = '';			
			document.getElementById('in_srcnew').value  = '';
			document.getElementById('in_delinfo').value = '';			
			document.getElementById('inPrevFrame').src = 'images/noImg.gif'; // update preview
		} else if (args[0] == 'tbox') { // image was saved to file			
			updatePreview(0);			
		}
		
		//-------------------------------------------------------------------------
		// update popup preview and set popup default attributes
		if (document.getElementById('mbtn_po').className == 'btnDown') {
			var popSrc = clib + cfile; 
			setImagePopup(popSrc);			
			formObj.popTitle.value = cfile.substr(0, cfile.length-4);			
		}
		//-------------------------------------------------------------------------
		updateStyle();		
		btnStage();
		changeClass(0,'ouDiv','showit','ciDiv','hideit');
		setCrm(); // set manual crop div
	}
// ============================================================
// = set popup image src preview V 1.0, date: 05/13/2005      =
// ============================================================	
	function setImagePopup(popSrc) {		
		var formObj = document.forms[0];
		var src = '<?php echo $cfg['scripts']; ?>' + 'phpThumb/phpThumb.php'; // command			
		src     = src + '?src=' + popSrc; 					// popup source image				
		src     = src + '&w=80'; 							// image width
		src     = src + '&h=60'; 							// image height
		src     = src + '&zc=1'; 							// zoom crop			
		document.getElementById('poPrevFrame').src = src; 		// update preview	
		formObj.popSrc.value = popSrc;
	}
// ============================================================
// = resize image to fit preview, date: 08/04/2005            =
// ============================================================	
	function resizePreview(w,h,mw,mh) { // width, height, max width, max height				
		var sizes = new Array();		
		if (w > mw || h > mh) { // thumbnailing required
			f = w / h; // proportions of image: (f > 1) = landscape; (f < 1) = portrait; (f = 1) = square			
			if (f > 1) { // landscape and square
				w = mw;
				h = Math.round(w / f);			
			} else if (f <= 1) {	// portrait
				h = mh;				
				w = Math.round(h * f);			
			}	
		}				
		sizes['w'] = parseFloat(w);
		sizes['h'] = parseFloat(h);
		return sizes;
	}
// ============================================================
// = update style frame V 1.0, date: 12/13/2004               =
// ============================================================	
	function updateStyle() {
		var formObj = document.forms[0];			
		document.getElementById('atPrevImg').align 	 	= formObj.pr_align.options[formObj.pr_align.selectedIndex].value;			
		document.getElementById('atPrevImg').vspace 	= formObj.pr_vspace.value;
		document.getElementById('atPrevImg').hspace 	= formObj.pr_hspace.value;
		document.getElementById('atPrevImg').border 	= formObj.pr_border.value;
		document.getElementById('atPrevImg').alt 		= formObj.pr_alt.value;
		document.getElementById('atPrevImg').title 	 	= formObj.pr_title.value;
		document.getElementById('atPrevImg').className 	= formObj.pr_class.options[formObj.pr_class.selectedIndex].value;	
	}
// ============================================================
// = get image type V 1.0, date: 11/27/2004                   =
// ============================================================	
	function imageType(type) {		
		var ext;		
		switch(parseInt(type)) {			
			case 1 : ext = 'gif'; break;
   			case 2 : ext = 'jpg'; break;
			case 3 : ext = 'png'; break;   			
			default: ext = 'unknown';		
		}			
		return ext;
	}
// ============================================================
// = get image type value  V 1.0, date: 05/02/2005            =
// ============================================================	
	function imageValue(type) {		
		var ext;		
		switch(type) {			
   			case 'gif'  : ext = 1; break;
			case 'jpg'  : ext = 2; break;
			case 'jpeg' : ext = 2; break;
			case 'png'  : ext = 3; break;
			default     : ext = 'unknown';		
		}			
		return ext;
	}	
// ============================================================
// = check resize dimensions V 1.0, date: 02/05/2005          =
// ============================================================	
	function rschkDim(obj) {		
		var formObj  = document.forms[0];
		var cwidth   = document.getElementById('cimg').attributes['cwidth'].value;			// get current width	
		var cheight  = document.getElementById('cimg').attributes['cheight'].value;			// get current height	
		
		if (formObj.pr_src.value == '') { // no valid image			
			alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
			return;
		}
				
		// setting constrain picture
		if (obj.type != 'text') {
			if (obj.id == 'rs_chkEnla' || obj.id == 'irsreset') {
				formObj.rs_width.value  = formObj.pr_width.value;
				formObj.rs_height.value = formObj.pr_height.value;
			} else if (obj.id == 'rs_type') {
				if (formObj.rs_type[0].checked == true) {				
					document.getElementById('irsreset').src = 'images/cona_off.gif';
					formObj.rs_width.value  = formObj.pr_width.value;
					formObj.rs_height.value = formObj.pr_height.value;
				} else {
					document.getElementById('irsreset').src = 'images/coni_off.gif';
				}
			}		
			formObj.rs_width.focus();
			return;		
		}
		
		// check if enlargement is allowed		
		if (formObj.rs_width.value > cwidth || formObj.rs_height.value > cheight) {			
			if (formObj.rs_chkEnla.checked == false) {									
				formObj.rs_width.value  = formObj.pr_width.value;
				formObj.rs_height.value = formObj.pr_height.value;
				formObj.rs_width.focus();
				alert('<?php echo $l->m('er_012'); ?>');
				return;		
			}			
		}
		
		// resize - calculate new dimensions		
		f = cheight/cwidth; // original aspect ratio of image					
		str = obj.id; 		// width or height field id				
		if (formObj.rs_type[0].checked == true) {								
			// recalculate dimensions	
			if (str.substring(3, str.length) == 'width') {			
				formObj.rs_height.value = Math.round(formObj.rs_width.value * f);
			} else if (str.substring(3, str.length) == 'height') {		
				formObj.rs_width.value = Math.round(formObj.rs_height.value / f);					
			}
		}
	}

// ============================================================
// = check crop dimensions V 1.0, date: 05/03/2006            =
// ============================================================	
 	function crchkDim(obj) {
		var formObj = document.forms[0];		
		var cfile   = document.getElementById('cimg').attributes['cfile'].value;	// get current image
		var cwidth  = document.getElementById('cimg').attributes['cwidth'].value;	// get current width	
		var cheight = document.getElementById('cimg').attributes['cheight'].value;	// get current height	
		
		if (cfile == '') { // no valid image			
			alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
			return;
		}
		f = cheight / cwidth; // original aspect ratio of image	
		if (obj.type == 'text') {
			str = obj.id;			 		
			// new size is > than original size	
			if (formObj.cr_width.value > parseInt(cwidth) || formObj.cr_height.value > parseInt(cheight)) {			
				formObj.cr_width.value  = Math.round(cwidth / 2);
				formObj.cr_height.value = Math.round(cheight / 2);
				alert('<?php echo $l->m('er_001'). ': ' . $l->m('er_036'); ?>');
				formObj.cr_width.focus();
				return;			 
			} else if (str == 'cr_top') {
				var tval = cheight - formObj.cr_height.value; 
				if(formObj.cr_top.value > tval) {				
					formObj.cr_top.value = 0;
					alert('<?php echo $l->m('er_034'); ?>: ' + tval);
					formObj.cr_top.focus();				
					return;		
				}		
			} else if (str == 'cr_left') {
				var tval = cwidth - formObj.cr_width.value;
				if(formObj.cr_left.value > tval) {				
					formObj.cr_left.value = 0;
					alert('<?php echo $l->m('er_035'); ?>: ' + tval);
					formObj.cr_left.focus();
					return;		
				}
			}							
				
			if (formObj.cr_ratio[0].checked) { // 4 x 3			
				if (str.substring(3,str.length) == 'width') {			
					formObj.cr_height.value = Math.round(formObj.cr_width.value * f);
				} else if (str.substring(3, str.length) == 'height') {		
					formObj.cr_width.value = Math.round(formObj.cr_height.value / f);					
				}			
			} else if (formObj.cr_ratio[1].checked) { // 1 x 1					
				if (str.substring(3,str.length) == 'width') {									
					formObj.cr_height.value = formObj.cr_width.value;
				} else if (str.substring(3, str.length) == 'height') {							
					formObj.cr_width.value = formObj.cr_height.value;	 
				}								
			}			
			
		} else if (obj.type == 'radio') { // resetting values							
			if (formObj.cr_ratio[0].checked) { // 4 x 3			
				formObj.cr_width.value  = Math.round(cwidth / 2);
				formObj.cr_height.value = Math.round(cheight / 2);		
			} else if (formObj.cr_ratio[1].checked) { // 1 x 1					
				if (f <= 1) { // landscape					
					formObj.cr_height.value = Math.round(cheight / 2);
					formObj.cr_width.value  = formObj.cr_height.value;
				} else { // portrait
					formObj.cr_width.value  = Math.round(cwidth / 2);
					formObj.cr_height.value = formObj.cr_width.value;
				}						
			}
		}
		// update small and large crop preview
		setCrm(); // set manual crop div
		if (document.getElementById('ciDiv').className == 'showit') { // update crop preview
			cropIt(0);
		}				
	}
// ============================================================
// = set check boxes & info screen V 1.0, date: 12/08/2004    =
// ============================================================	
	function setchkBox() {			
		var imgOn  = 'images/infa_off.gif';
		var imgOff = 'images/infi_off.gif';	
		var args = setchkBox.arguments;
		var t = false;		
 		if (document.getElementById) {
  			for (var i = 1; i < args.length; i++) {			
				if (document.getElementById(args[i]) != null) {					
					str = document.getElementById(args[i]).id; // field					
					if (str.substring(0,2) == 'i_') { // info screen
						var fld = str.substring(2, str.length);
						var img = str +'_img';					
					} else {
						var fld = 'i_' + str;
						var img = 'i_' + str + '_img';	
					}					
					// set parent status				
					if (document.getElementById(args[i]).checked) {
						document.getElementById(args[i]).checked = true;
						t = true;
					} else {
						document.getElementById(args[i]).checked = false;
					}
					// set check boxes & images					
					if (document.getElementById(fld)) {
						document.getElementById(fld).checked = document.getElementById(args[i]).checked;	
						if (document.getElementById(fld).checked == true) {
							document.getElementById(img).src = imgOn;			
						} else {
							document.getElementById(img).src = imgOff;			
						}
					}  					
				}
			}			
		}		
		// set parent
		if (document.getElementById(args[0])) {
			str = document.getElementById(args[0]).id; // parent field					
			if (str.substring(0, 2) == 'i_') { // info screen
				var fld = str.substring(2, str.length);
				var img = str +'_img';					
			} else {
				var fld = 'i_' + str;
				var img = 'i_' + str + '_img';	
			}					
			if (t == true) {
				document.getElementById(args[0]).checked = true;
				document.getElementById(fld).checked = true;						
				document.getElementById(img).src = imgOn;			
			} else {
				document.getElementById(args[0]).checked = false;
				document.getElementById(fld).checked = false;	
				document.getElementById(img).src = imgOff;								
			}
		} 
	}	
// ============================================================
// = set color V 1.0, date: 03/21/2005                        =
// ============================================================		
	function selColor(obj) {		
		str = obj.id;			
		var cfld = str.substring(0, 3) + '_col'; // color field code 		
		var ifld = str.substring(0, 3) + '_icol'; // background color
		gcfld = cfld;  // setting current color code field id
		gifld = ifld;  // setting current color field id		
		if (obj.type.toLowerCase() == 'button') { // show color picker			
			curcolor = document.getElementById(cfld).value;			
			var wArgs = {}
			wArgs.iManager = iManager;
			wArgs.curcolor = curcolor;
			if (iManager.isMSIE) { 
				var newcol = showModalDialog('<?php echo $cfg['scripts']; ?>colorpicker.php?lang=<?php echo $l->lang; ?>', wArgs, 
				'dialogHeight:250px; dialogWidth:366px; scrollbars: no; menubar: no; toolbar: no; resizable: no; status: no;');													
				if (newcol != null) {
					setColor(null,null,newcol);
				}	
			} else if (iManager.isGecko) { 
				var wnd = window.open('<?php echo $cfg['scripts']; ?>colorpicker.php?lang=<?php echo $l->lang; ?>&callback=setColor', 'colors', 'status=no, modal=yes, width=400, height=300'); 
				wnd.dialogArguments = wArgs;
			}					 						
		} else {
			// color code field has changed
			var newcol = document.getElementById(cfld).value;				
			setColor(null, null, newcol);			
		}		
	}	
	//-------------------------------------------------------------------------
	function setColor(editor, sender, color) {
		if (color) { // IE
			var newcol = color;			
		} else { // firefox
			var newcol = sender.returnValue;			
		}
		
		var cfld = gcfld;
		var ifld = gifld;		
		if (IsValidHexColor(newcol)) {			
			document.getElementById(cfld).value = newcol;
			document.getElementById(ifld).style.backgroundColor = newcol;
			synColor(cfld);			
		} else {
			alert('<?php echo $l->m('er_001'). ': ' . $l->m('er_013'); ?>: ' + document.getElementById(cfld).value);			
			var str = document.getElementById(ifld).style.backgroundColor;			
			if (str.substring(0,1) != '#') { // firefox returns rgb value, no matter what				
				var a = str.split(/\s*,\s*/);
  				if (a) {
    				for (var i = a.length; i--;) {
      					a[i] = a[i].replace(/(rgb|[()]|\s+)/g, '');
					}
    			}			
				str = '#' + RGB2hex(a[0],a[1],a[2]);				
			}						
			document.getElementById(cfld).value = str;					
			return;
		}
	}
// ============================================================
// = sync bg color fields V 1.0, date: 03/18/2005             =
// ============================================================	
	function synColor(cfld) {		
		var args = new Array('rs0_col','or0_col','ms0_col','br0_col','rc0_col','el0_col'); // background color fields		
		if ((args.join().indexOf(cfld)) != -1) {		
			for (var i = 0; i < args.length; i++) {								
				 if (args[i] != cfld) {				 	
					document.getElementById(args[i]).value = document.getElementById(cfld).value;
					str = args[i].substring(0, 3) + '_icol';					
					document.getElementById(str).style.backgroundColor = document.getElementById(cfld).value;					
				} 
			}
		}
		return;
	}
// ============================================================
// = set symbol V 1.0, date: 04/28/2005                       =
// ============================================================		
	function selSymbol(elm) {				
		var wArgs = {};
		wArgs.iManager = iManager;
		wArgs.elm = elm;	// passing calling element to function
		if ((iManager.isMSIE)) { 
			var rArgs = showModalDialog('<?php echo $cfg['scripts']; ?>symbols.php?lang=<?php echo $l->lang; ?>', wArgs, 
			'dialogHeight:300px; dialogWidth:400px; scrollbars: no; menubar: no; toolbar: no; resizable: no; status: no;');													
			if (rArgs) {				
				setSymbol(null, null, rArgs);
			}			
		} else if (iManager.isGecko) {
			var wnd = window.open('<?php echo $cfg['scripts']; ?>symbols.php?lang=<?php echo $l->lang; ?>&callback=setSymbol', 'symbols', 'status=no, modal=yes, width=400, height=300');				
			wnd.dialogArguments = wArgs;
		}		
	}
	// set symbol callback
	function setSymbol(editor, sender, rArgs) {		
		if (!rArgs) { // Gecko		
			var rArgs = sender.returnValue;				
		}
		if (rArgs.chr != null) {
			var chr = rArgs.chr;
			var elm = rArgs.elm;				
			chr = String.fromCharCode(chr.substring(2, chr.length -1)); // e.g. returns &#220;		
			document.getElementById(elm).value = document.getElementById(elm).value + ' ' + chr;
		}			
  }		
// ============================================================
// = crop image V 1.0, date: 11/26/2004                       =
// ============================================================
	function cropIt(action) {
		var formObj = document.forms[0];		
		var clib    = formObj.ilibs.options[formObj.ilibs.selectedIndex].value; 			// current library
		var cfile   = document.getElementById('cimg').attributes['cfile'].value; 			// current image		
		
		if (action == 0) { 																	// load crop interface		
			var src = '<?php echo $cfg['scripts'] ;?>' + 'phpCrop/crop.php'; 				// command
			src = src + '?s=' + absPath(clib) + cfile; 										// source file			
			src = src + '&w=' + formObj.cr_width.value+'&h=' + formObj.cr_height.value; 	// width, height of crop area
			src = src + '&x=' + formObj.cr_left.value+'&y=' + formObj.cr_top.value; 		// top, left values of crop area			
			src = src + '&d=<?php echo addslashes($cfg['temp']); ?>';				
			for (var i = 0; i < formObj.cr_ratio.length; i++) {
   				if (formObj.cr_ratio[i].checked) {
      				var ratio = formObj.cr_ratio[i].value;
      			}
  			}
			if (ratio != 0) {
				src = src + '&r=1';
			} else {
				src = src + '&r=0';
			}			
			formObj.cr_ibtn.src = 'images/crop_off.gif';			
			document.getElementById('crPrevFrame').src = src;			
			changeClass(1,'ouDiv','hideit','ciDiv','showit');
			formObj.cropstat.value = 1;				
		} else if (action == 1) { 															// do crop
			if (formObj.cropstat.value == 1) {		
				parent.top.frames.crPrevFrame.my_Submit();			
				formObj.cropstat.value = 0;
				formObj.cr_ibtn.src = 'images/crop.gif';						
			}		
		} else if (action == 2) { 															// exit crop interface
			if (formObj.cropstat.value != '') {
				formObj.chkCrop.checked = true;
				setchkBox('chkCrop','chkCrop');
				formObj.refresh.click();				
				changeClass(1,'ouDiv','showit','ciDiv','hideit');				
				formObj.cropstat.value = 0;
			}
		}		
	}	
// ============================================================
// = process toolbox settings V 1.0, date: 12/02/2004         =
// ============================================================
	function updatePreview(action) {		
		var formObj = document.forms[0];		
		var clib    = absPath(formObj.ilibs.options[formObj.ilibs.selectedIndex].value);				// current library - absolute path		
		var cfile   = document.getElementById('cimg').attributes['cfile'].value;						// get current image
		var tbox 	= false;																			// reset toolbox
		var src 	= ''; 																				// reset src value				
      	var ctype 	= imageType(formObj.sel_oFormat.options[formObj.sel_oFormat.selectedIndex].value); 	// get output file type - e.g. '.jpg'      			
		
		// temporary resize to  preview	max
		if (action == 0) { // refresh clicked			
			src = '<?php echo $cfg['scripts']; ?>' + 'phpThumb/phpThumb.php'; 		// command
			src = src + '?src=' + clib + cfile; // source image
			var sizes;		
			sizes = resizePreview(formObj.rs_width.value,formObj.rs_height.value,150,150);			
			w = sizes['w'];
			h = sizes['h'];			
		} else if (action == 1) { // large image preview			
			src = '<?php echo $cfg['scripts']; ?>' + 'phpThumb/phpThumb.php'; 		// command
			src = src + '?src=' + clib + cfile; // source image	
			var sizes;		
			sizes = resizePreview(formObj.rs_width.value,formObj.rs_height.value,512,512);
			w = sizes['w'];
			h = sizes['h'];					
		} else if (action == 2) { // accept changes => render image to file
			if ('<?php echo $mode; ?>' == 1) { // mode: plugin 
				src = '<?php echo $cfg['scripts']; ?>' + 'phpWizard.php'; 			// command
				src = src + '?src=' + clib + cfile; // source image	
				if (formObj.chk_oNewFile.checked == true) {
					var nfi = 1; // original file will be overwritten
					src = src + '&nfi=' + nfi;
				}
			
			} else if ('<?php echo $mode; ?>' == 2) { // mode: standalone; render image to local file					
				src = '<?php echo $cfg['scripts']; ?>' + 'phpThumb/phpThumb.php'; 	// command
				src = src + '?src=' + clib + cfile; // source image	
				src = src + '&down=' + cfile.substr(0, cfile.length-3) + ctype; // destination image				
			}			
			// use resize values for output
			w = formObj.rs_width.value;
			h = formObj.rs_height.value;
		}			
		
		// crop image
		if (formObj.chkCrop.checked) { // apply crop 			
			changeClass(0,'ouDiv','showit','ciDiv','hideit'); 		// close crop interface in case it's open	
			src = src + '&sx=' + formObj.cr_left.value; 			// crop left (x)
			src = src + '&sy=' + formObj.cr_top.value; 				// crop top (y)
			src = src + '&sw=' + formObj.cr_width.value; 			// crop width
			src = src + '&sh=' + formObj.cr_height.value; 			// crope height			
			tbox = true;			
		}
		//-------------------------------------------------------------------------
		// common values
		src = src + '&w=' + w; 		// source width - height value is only needed if ignore aspect ratio is set		
		src = src + '&f=' + ctype; 	// output file format (jpg, png, or gif)
		if(ctype == 'jpg') {		// quality - only for jpg
			src = src + '&q=' + formObj.sel_oQuality.options[formObj.sel_oQuality.selectedIndex].value; 			
		}
		if (ctype != 'png') { 		// set background color if type not 'png' file
			src = src + '&bg=' + formObj.or0_col.value.substring(1); // background color;
		}		
		//-------------------------------------------------------------------------
		// resize image
		if (formObj.chkResize.checked) { // apply resize
			// dimensions
			for (var i =1; i < formObj.rs_type.length; i++) { // resize type (keep proportions, ignore aspect ratio, force aspect ratio, zoom crop)
				if (formObj.rs_type[i].checked) {
					var type = formObj.rs_type[i].value;
					src = src + '&' + type + '=1';
					src = src + '&h=' + h; // height					
				}   				
			}			
			// allow enlargment (proportional resizing and zoom crop)			
			if (formObj.rs_chkEnla.checked) {				
				if (formObj.rs_type[0].checked || formObj.rs_type[3].checked) {
					src = src + '&' + formObj.rs_chkEnla.value + '=1';
				}				
			}								
			tbox = true;
		}	
		//-------------------------------------------------------------------------
		// orientation (rotate/flip) 
		if (formObj.chkOrientation.checked) { // apply orientation	
			// flip
			if (formObj.chkFlip.checked) {				
				// &fltr[]=flip|x or y or xy
				// horizontal, vertical or both 				
				f = formObj.or_flip.options[formObj.or_flip.selectedIndex].value;				
				src = src + '&fltr[]=flip|' + f; // flip type
				tbox = true;	
			}			
			// rotate			
			if (formObj.chkRotate.checked) {				
				for (var i = 0; i <  formObj.ro_type.length; i++) { // orientation type (flip or rotate)
   					if (formObj.ro_type[i].checked) {
      					var type = formObj.ro_type[i].value;
      				}
   				}			
				if (type == 0) { // rotate by angle
					// &ra=<value>								
					src = src + '&h=' + w; // height 
					src = src + '&ra=' + formObj.ro_angle.options[formObj.ro_angle.selectedIndex].value; // rotate angle						
					tbox = true;					
				} else if (type == 1) { // auto rotate				
					// ar=<type>					
					src = src + '&h=' + w; // height 
					src = src + '&ar=' + formObj.ro_auto.options[formObj.ro_auto.selectedIndex].value; // auto rotate type									
					tbox = true;			 			
				}						
			}			
		}
		//-------------------------------------------------------------------------
		// colorize
		if (formObj.chkColorize.checked) { // colorize selected  
			// image effects
			if (formObj.chkEffects.checked) { // image effect selected 
				// determine value of checked radio button for color
				for (var i = 0; i < formObj.co_type.length; i++) {
   					if (formObj.co_type[i].checked) {
      					var type = formObj.co_type[i].value;
      				}
   				}					
				
				if (type == 0) { // greyscale == default					
					src = src + '&fltr[]=ds|100'; // desaturation = 100
					tbox = true;					
				} else if (type == 1) { // negative				
					// &fltr[]=neg
					src = src + '&fltr[]=neg';
					tbox = true;				
				} else if (type == 2) { // threshold				
					// &fltr[]=th|<value>
					// level
					l = formObj.tb_thres.value;
					src = src + '&fltr[]=th|' + l;
					tbox = true;
				} else if (type == 3) { // sepia
					// &fltr[]=sep|<value>|<color>
					// intensity, color
					v = formObj.se_in.value;
					c = formObj.se0_col.value.substring(1);					
					src = src + '&fltr[]=sep|' + v + '|' + c;
					tbox = true;										
				} else if (type == 4) { // colorize
					// &fltr[]=clr|<value>|<color>
					// intensity, color
					v = formObj.co_in.value;
					c = formObj.co0_col.value.substring(1);					
					src = src + '&fltr[]=clr|' + v + '|' + c;
					tbox = true;				
				}						
			}
			//-------------------------------------------------------------------------
			// touchup
			if (formObj.chkTouchup.checked) { // image touchup selected
				// gamma
				if(formObj.chkGamma.checked) { // gamma selected
					// &fltr[]=gam|<value>					
					v = formObj.co_ga.options[formObj.co_ga.selectedIndex].value;	
					src = src + '&fltr[]=gam|' + v;
					tbox = true;
				}
				// saturation
				if (formObj.chkSaturation.checked) { // desaturation selected
					// &fltr[]=ds|<value>									
					v = formObj.co_ds.options[formObj.co_ds.selectedIndex].value;
					src = src + '&fltr[]=ds|' + v;
					tbox = true;
				}
				// blur
				if (formObj.chkBlur.checked) { // blur selected
					// &fltr[]=blur|<radius>									
					r = formObj.co_bl.options[formObj.co_bl.selectedIndex].value;
					src = src + '&fltr[]=blur|' + r;
					tbox = true;
				}
				// unsharp mask
				if (formObj.chkUnsharp.checked) { // unsharp mask selected
					// &fltr[]=usm|<amount>|<radius>|<threshold>					
					a = formObj.wz_usa.options[formObj.wz_usa.selectedIndex].value;
					r = formObj.wz_usr.options[formObj.wz_usr.selectedIndex].value;
					t = formObj.wz_ust.options[formObj.wz_ust.selectedIndex].value;				
					src = src + '&fltr[]=usm|' + a + '|' + r + '|' + t;			
					tbox = true;
				}				
				// level			
				if (formObj.chkLevel.checked) { // apply level				
					//&fltr[]=lvl|<channel>|<min>|<max>						
					c = formObj.wz_cha.options[formObj.wz_cha.selectedIndex].value;
					m = formObj.wz_min.options[formObj.wz_min.selectedIndex].value;
					x = formObj.wz_max.options[formObj.wz_max.selectedIndex].value;			
					src = src + '&fltr[]=lvl|' + c + '|' + m + '|' + x;
					tbox = true; 
				}
				// white balance
				if (formObj.chkWhiteB.checked) { // apply white balance
					//&fltr[]=wb|<c>]
					c = formObj.wb0_col.value.substring(1);	 
					src = src + '&fltr[]=wb|' + c;											
				}							
			}
		}
		//-------------------------------------------------------------------------
		// watermark
		if (formObj.chkWatermark.checked) { // apply watermark to image
			// determine value of checked radio button of watermark options
			for (var i = 0; i < formObj.wm_type.length; i++) {
   				if (formObj.wm_type[i].checked) {
      				var type = formObj.wm_type[i].value; // watermark options
      			}
   			}			
			if (type == 0) { // watermark text selected				
				// &fltr[]=wmt|<text>|<size>|<align>|<color>|<font>|<opacity>|<margin>|<angle>	
				for (var i = 0; i < formObj.wmf_type.length; i++) { // font (system or true type)
   					if (formObj.wmf_type[i].checked) {
      					var font = formObj.wmf_type[i].value;
      				}
   				}								
				if (formObj.wm_text.value == '') { // empty watermark text
					var curdate = new Date();					
					document.forms[0].wm_text.value = String.fromCharCode(169) + ' Copyright, ' + curdate.getYear();
				}
				
				t = escape(formObj.wm_text.value);
				a = formObj.wm_align.options[formObj.wm_align.selectedIndex].value;
				o = formObj.wm_opacity.options[formObj.wm_opacity.selectedIndex].value;
				m = formObj.wm_space.options[formObj.wm_space.selectedIndex].value;				
				c = formObj.wm0_col.value.substring(1);
				
				if (font == 0) { // system font
					s   = formObj.wms_size.options[formObj.wms_size.selectedIndex].value;					
					f   = '';
					src = src + '&fltr[]=wmt|' + t + '|' + s + '|' + a + '|' + c + '|' + f + '|' + o +'|' + m + '|' + 0; 
				} else if (font == 1) { // true type				
					f   = formObj.wm_font.options[formObj.wm_font.selectedIndex].value;
					n   = formObj.wm_angle.options[formObj.wm_angle.selectedIndex].value;
					s   = formObj.wm_size.options[formObj.wm_size.selectedIndex].value;					
					src = src + '&fltr[]=wmt|' + t + '|' + s + '|' + a + '|' + c + '|' + f + '|' + o + '|' + m + '|' + n;  
				}				
				tbox = true;				
				
			} else if (type == 1) { // watermark image selected
				// &fltr[]=wmi|<file>|<align>|<opacity>|<margin>				
				for (var i = 0; i < formObj.wmf.length; i++) {
   					if (formObj.wmf[i].checked) {
      					var wmf = formObj.wmf[i].value; // watermark options
      				}
   				}
				if (wmf == null) { // no watermark image has been selected
					alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_017'); ?>');
					return;
				}											
				f    = wmf;
				a    = formObj.wm_align.options[formObj.wm_align.selectedIndex].value
				o    = formObj.wm_opacity.options[formObj.wm_opacity.selectedIndex].value;
				m    = formObj.wm_space.options[formObj.wm_space.selectedIndex].value;
				src  = src + '&fltr[]=wmi|' + f + '|' + a + '|' + o + '|' + m;
				tbox = true; 								
			}			
		}
		//-------------------------------------------------------------------------
		// overlay				
		if (formObj.chkOverlay.checked) { // apply overlay checked		
			//&fltr[]=over|<file>|<overlay/underlay>|<margin>|<opacity>				
			// determine value of checked radio button of arrange options
			for (var i = 0; i < formObj.ov_type.length; i++) {
   				if (formObj.ov_type[i].checked) {
      				var type = formObj.ov_type[i].value; // overlay options
      			}
   			}						
			if (formObj.ovFile.value == '') { // no overlay image has been selected
				alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_040'); ?>');
				return;
			}			
			i    = formObj.ovFile.value; // overlay file			
			u    = type;
			m    = formObj.ov_space.options[formObj.ov_space.selectedIndex].value; // margin
			o    = formObj.ov_opacity.options[formObj.ov_opacity.selectedIndex].value; // opacity				
			src  = src + '&fltr[]=over|' + i + '|' + u + '|' + m + '|' + o; 
			tbox = true;
		}							
		//-------------------------------------------------------------------------
		// mask
		if (formObj.chkMask.checked) { // apply mask to image
			// determine value of checked radio button of mask images
			for (var i = 0; i < formObj.msf.length; i++) {
   				if (formObj.msf[i].checked) {
      				var msf = formObj.msf[i].value; // mask image
      			}
   			}				
			if (msf == null) { // no mask image has been selected
				alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_018'); ?>');
				return;
			}
			// &fltr[]=mask					
			src  = src + '&fltr[]=mask|' + msf; // mask file			
			tbox = true;
		}
		//-------------------------------------------------------------------------		
		// image wizard
		if (formObj.chkWizard.checked) { // image wizard checked			
			// bevel
			if (formObj.chkBevel.checked) { // apply bevel checked
				//&fltr[]=bvl|<width>|<color left & top>|<color right & bottom>				
				w    = formObj.wz_BevelWidth.options[formObj.wz_BevelWidth.selectedIndex].value;
				c1   = formObj.be0_col.value.substring(1);
				c2   = formObj.be1_col.value.substring(1);							
				src  = src+'&fltr[]=bvl|' + w + '|' + c1 + '|' + c2; 
				tbox = true;
			}
			//-------------------------------------------------------------------------			
			// frame				
			if (formObj.chkFrame.checked) { // apply frame checked
				//&fltr[]=fram|<width main border>|<width of each side>|<color main border>|<highlight bevel color>|<shadow color>				
				w1   = formObj.fr_w.options[formObj.fr_w.selectedIndex].value;
				w2   = formObj.fr_wb.options[formObj.fr_wb.selectedIndex].value;
				c1   = formObj.fr0_col.value.substring(1);
				c2   = formObj.fr1_col.value.substring(1);
				c3   = formObj.fr2_col.value.substring(1);	
				src  = src + '&fltr[]=fram|' + w1 + '|' + w2 + '|' + c1 + '|' + c2 + '|' + c3;
				tbox = true;
			}
			//-------------------------------------------------------------------------
			// round corner			
			if (formObj.chkCorner.checked) { // apply rounded corners							
				//&fltr[]=ric|<x>|<y>				
				x    = formObj.rcxrad.options[formObj.rcxrad.selectedIndex].value; 		// x edge radius
				y    = formObj.rcyrad.options[formObj.rcyrad.selectedIndex].value; 		// y edge radius	
				src  = src + '&fltr[]=ric|' + x + '|' + y; 								// filter command				
				tbox = true;				
			}
			//-------------------------------------------------------------------------			
			// round border			
			if (formObj.chkBorder.checked) { // apply rounded borders				
				//&fltr[]=bord|<width>|<x radius>|<y radius>|<color>
				w    = formObj.brwidth.options[formObj.brwidth.selectedIndex].value; 	// width
				x    = formObj.brxrad.options[formObj.brxrad.selectedIndex].value; 		// x edge radius
				y    = formObj.bryrad.options[formObj.bryrad.selectedIndex].value; 		// y edge radius		
				c    = formObj.br1_col.value.substring(1); 								// border color
				src  = src + '&fltr[]=bord|' + w + '|' + x + '|' + y + '|' + c; 		// filter command				
				tbox = true;				
			}
			//-------------------------------------------------------------------------			
			// ellipse				
			if (formObj.chkEllipse.checked) { // apply ellipse checked		
				//&fltr[]=elip				
				src  = src + '&fltr[]=elip'; 											// filter command				
				tbox = true;
			}				
			//-------------------------------------------------------------------------
			// drop shadow			
			if (formObj.chkShadow.checked) { // apply shadow checked				
				//&fltr[]=drop|<distance>|<width>|<color>|<angle>|<fade>							
				d    = formObj.sh_margin.options[formObj.sh_margin.selectedIndex].value;
				w    = formObj.wz_ShadowWidth.options[formObj.wz_ShadowWidth.selectedIndex].value;
				clr  = formObj.ds0_col.value.substring(1);
				a    = formObj.sh_angle.options[formObj.sh_angle.selectedIndex].value;
				f    = formObj.sh_fade.options[formObj.sh_fade.selectedIndex].value;
				src  = src + '&fltr[]=drop|' + d + '|' + w + '|' + clr + '|' + a + '|' + f;
				tbox = true;
			}						
		}
		//-------------------------------------------------------------------------
		// process toolbox settings and update preview	
		if (tbox) { // if toolbox action has been selected			
			//src = src + '&phpThumbDebug=9'; // debug phpThumb				
			if (action == 1) { // src for popup window							
				return src;				
			}						
			if (action == 2) { // save image to file - refresh screen				
				if ('<?php echo $mode; ?>' == 1) { 					// plugin mode					
					document.getElementById('inPrevFrame').src = src; 	// execute command									
					formObj.param.value = 'tbox' + '|' + cfile; 	// parameter: <action>|<file>						
					formObj.submit();														
					return;
				} else if ('<?php echo $mode; ?>' == 2) { 			// standalone mode - save file to local disk					
					document.getElementById('inPrevFrame').src = src;	// execute command					
					updatePreview(0); 								// refresh preview screen
					return;									
				}
			}				
			document.getElementById('tbPrevFrame').src = src; 			// execute command				
		} else { // no toolbox action selected - resetting image			
			document.getElementById('tbPrevFrame').src = src; 			// execute command			
			alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_020'); ?>');
			return;
		}
	}
// ============================================================
// = preload Images, date: 11/13/2004                         =
// ============================================================		
	function preloadImages() {
  		var d=document;
		if(d.images) {
			if(!d.MM_p)
				d.MM_p = new Array();
    			var i,j=d.MM_p.length,a = preloadImages.arguments;
				for(i= 0; i < a.length; i++)
    				if (a[i].indexOf("#") != 0) {
						d.MM_p[j] = new Image;
						d.MM_p[j++].src = a[i];
			}
		}
	}
// ============================================================
// = change image library V 1.0, date: 04/22/2005             =
// ============================================================
	function ilibsClick() {		
		var formObj = document.forms[0];		
		formObj.param.value = ''; // clear param values;		
		formObj.submit();	
		// reset values 
		document.getElementById('inPrevFrame').src = 'images/noImg.gif'; // update preview
		document.getElementById('cimg').attributes['cfile'].value = '';
		btnStage();			
	}
// ============================================================
// = change overlay image library V 1.0, date: 05/05/2005     =
// ============================================================		
	function ov_ilibsClick(clib, cfile) {		
		var formObj = document.forms[0];		
		var src = '<?php echo $cfg['scripts']; ?>' + 'ov_rfiles.php';
		src = src + '?clib=' + clib;		
		if (cfile != '') { // image is dynamic thumbnail				
			src = src + '&param=' + 'update' + '|' + cfile;		
		} else {
			formObj.ovFile.value = ''; // reset overlay file if library change
			formObj.chkOverlay.checked = false;
		}
		document.getElementById('ovPrevFrame').src = src; // update preview			
	} 
// ============================================================
// = upload image, date: 05/24/2005                           =
// ============================================================
	function uploadClick() {
		var formObj = document.forms[0];		
		if (!checkUpload()) {
			alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_023'); ?>');
			return;
		}
		if (confirm('<?php echo $l->m('er_021'); ?>')) {			
			showloadmessage(); // show load message
			formObj.param.value = 'upload'; // parameter: <action>			
			formObj.submit();						
		}
	}
	// check whether image file is selected for uploading
	function checkUpload() {		
		var formObj = document.forms[0];	
		var upload = false;
		var x = document.getElementById('fiUplDiv').getElementsByTagName('input');
		for (var i = 0; i < x.length; i++) {
			if (x[i].type == 'file') {
				if (x[i].value != '') { // check whether files has been selected for upload					
					
					for (z=0; document.getElementById('chkThumbSize['+ z +']'); z++) {						
						if(document.getElementById('chkThumbSize['+ z +']').checked) {
							upload = true;							
						}						
					}
				}
			}			
		}
		return upload;	
	}
// ============================================================
// = delete image V 1.0, date: 04/22/2005                     =
// ============================================================
	function deleteClick() {
		var formObj = document.forms[0];		
		var cfile = document.getElementById('cimg').attributes['cfile'].value;
		if (cfile == '') { // check if image is selected	
			alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
			return;
		}
				
		if (confirm('<?php echo $l->m('er_008'); ?> ' + cfile + '!')) {				
			formObj.param.value = 'delete' + '|' + cfile; // parameter: <action>|<file>				
			formObj.submit();	
		}	  	
	} 
// ============================================================
// = rename image V 1.0, date: 04/22/2005                     =
// ============================================================
	function renameClick() {
		var formObj = document.forms[0];
		var clib =  formObj.ilibs.options[formObj.ilibs.selectedIndex].value; // current library
		var cfile = document.getElementById('cimg').attributes['cfile'].value;		
		var ctype = document.getElementById('cimg').attributes['ctype'].value.split('|');		
				
		if (cfile == '') { // check if image is selected
			alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
			return;
		}
		
		var ctype = '.' + imageType(ctype[0]);		
		if (formObj.in_srcnew.value == '' || formObj.in_srcnew.value + ctype == cfile) { // new name is either empty or hasn't changed
			alert('<?php echo $l->m('er_011'); ?>');
			return;
		}
				
		if (confirm('<?php echo $l->m('er_010'); ?>: ' + formObj.in_srcnew.value + ctype)) { // do rename					
			var nfile = formObj.in_srcnew.value + ctype;			 
			formObj.param.value = 'rename' + '|' + cfile + '|' + nfile; // parameter: <action>|<filename>|<newname>		
			formObj.submit();				
		}		  	
	}
// ============================================================
// = switch list view V 1.0, date: 07/06/2005                 =
// ============================================================
	function switchList() {
		var formObj = document.forms[0];			
		if (formObj.flist.value == 1) { // check if image is selected	
			formObj.flist.value = 0;
		} else {
			formObj.flist.value = 1;
		}		
		// refresh list view		
		var cfile = document.getElementById('cimg').attributes['cfile'].value;
		if (cfile.length > 0) {
			formObj.param.value = 'switch' + '|' + cfile;	
		}
		formObj.submit();	
	} 
// ============================================================
// = create new directory V 1.0, date: 04/22/2005             =
// ============================================================
	function createClick() {
		var formObj = document.forms[0];
		var clib    = formObj.ilibs.options[formObj.ilibs.selectedIndex].value; 		// current library
		
		if (clib == '') { // check if library is selected
			alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_002'); ?>');
			return;
		}			
		if (formObj.in_dirnew.value == '') { // check if user has entered a new directory name
			alert('<?php echo $l->m('er_011'); ?>');
			return;
		}
				
		if (confirm('<?php echo $l->m('in_026'); ?>: ' + clib + formObj.in_dirnew.value)) {					
			var nfile = formObj.in_dirnew.value;						 
			formObj.param.value = 'create' + '|' + nfile; // parameter: <action>|<newdir>		
			formObj.submit();				
		}				
	}
// ============================================================
// = full size preview V 1.0, date: 06/06/2005                =
// ============================================================	
	function fullSizeView(args) {		
		var formObj = document.forms[0];
		var src;
		var features;		
		
		if (args == 'in' || args == 'tb') { 											// image insert or image toolbox		
			var clib    = formObj.ilibs.options[formObj.ilibs.selectedIndex].value; 	// current library
			var cfile   = document.getElementById('cimg').attributes['cfile'].value; 	// current image			
			var cwidth  = document.getElementById('cimg').attributes['cwidth'].value;	// current width
			var cheight = document.getElementById('cimg').attributes['cheight'].value;	// current height
			if (cfile.length == 0) { 													// no valid image file
				return;
			}
		
			var sizes;		
			sizes = resizePreview(cwidth,cheight,512,512);			
			if (sizes['w'] > 150 || sizes['h'] > 150) { // open external window if size &gt; 150 which is the size of the preview window			
			} else {
				alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_038'); ?>');
				return;
			}
			features = 'width=' + sizes['w'] + ',height=' + sizes['h'];    		
		} else {
			features = 'width=180' + ',height=180'; 		
		}
		
		if (args == 'in') { 															// regular preview
			src = '<?php echo $cfg['scripts']; ?>' + 'phpThumb/phpThumb.php'; // command							
			src = src + '?src=' + absPath(clib) + cfile; // source image
			src = src + '&w='+sizes['w']; //image width									
		
		} else if (args == 'tb') { 														// toolbox preview
			src = updatePreview(1);			
		
		} else if (args == 'ov') { 														// overlay preview
			var ovf = formObj.ovFile.value;
			if (ovf.length == 0) {														// no valid overlay
				return;
			}
			src = '<?php echo $cfg['scripts']; ?>' + 'phpThumb/phpThumb.php'; // command							
			src = src + '?src=' + ovf; 	// source image
			src = src + '&w=512'; 		//image width			
		} else if (args == 'ms') { 														// mask preview	
			for (var i = 0; i < formObj.msf.length; i++) {
				if (formObj.msf[i].checked) {
					var msf = formObj.msf[i].value;
				}
			}
			if (msf.length == 0) {														// no valid mask
				return;
			}
			src = '<?php echo $cfg['scripts']; ?>' + 'phpThumb/phpThumb.php'; // command							
			src = src + '?src=' + msf; 	// source image
			src = src + '&w=512'; 		//image width			
		}				
		//-------------------------------------------------------------------------
		var windowName = 'fullView';							
		features = features +			  		
		',top='         + '10'  +
		',left='        + '10'  +
		',location='    + 'no'  +
		',menubar='     + 'no'  +
		',scrollbars='  + 'no'  +
		',status='      + 'no'  +
		',toolbar='     + 'no'  +
		',resizable='   + 'no';			
		// open full view popup window
		window.open('<?php echo $cfg['pop_url']; ?>?url=' + escape(src) + '&clTxt=' + '<?php echo $l->m('in_036'); ?>', windowName, features);				
	}
// ============================================================
// = change class, date: 12/01/2004                           =
// ============================================================
	function changeClass() {		
 		var args = changeClass.arguments; 		
		if (args[0] == 0 || args[0] == 1) { // 0 = no resizeDialogToContent; 1 = resizeDialogToContent
			var start = 1;
		} else {
			var start = 0;
		}
		
		for(var i = start; i < args.length; i += 2) {
			if(document.getElementById(args[i])!= null) {
				document.getElementById(args[i]).className=args[i+1];
			}
		}
		
		if (args[0] == 1) {					
			resizeDialogToContent();
		}		
	}
// ============================================================
// = image dimension change, date: 05/08/2005                 =
// ============================================================		
	function changeDim(sel) {		
		var formObj = document.forms[0];
		var cwidth  = document.getElementById('cimg').attributes['cwidth'].value;			// get current width	
		var cheight = document.getElementById('cimg').attributes['cheight'].value;			// get current height	
		
		if (eval(formObj.pr_width.value) > cwidth || eval(formObj.pr_height.value) > cheight) { 		// check for enlarging			
			alert('<?php echo $l->m('er_001') . ': ' . $l->m('er_043'); ?>')
			resetDim();
			return;
		}		
		
		f = cheight/cwidth; // factor		
		if (sel == 1) { 																	// height changed				
			formObj.pr_width.value  = Math.round(formObj.pr_height.value / f);
		} else if (sel == 0) { 																// width changed			
			formObj.pr_height.value = Math.round(formObj.pr_width.value * f);			
		}		
	}
	
	function resetDim() { // reset dimensions
 		var formObj = document.forms[0];
		var cwidth  = document.getElementById('cimg').attributes['cwidth'].value;			// get current width	
		var cheight = document.getElementById('cimg').attributes['cheight'].value;			// get current height	
		formObj.pr_width.value  = cwidth;
		formObj.pr_height.value = cheight;
	}
// ============================================================
// = enable/disable menu buttons, date: 03/21/2005            =
// ============================================================ 
	function btnStage() {
		var formObj = document.forms[0];	
		var cfile   = document.getElementById('cimg').attributes['cfile'].value; // get current image	
						
		if ('<?php echo $mode; ?>' == 1) { // plugin mode						
			if (cfile == '') { // no image is selected			
				formObj.img_at.src = 'images/img_at_off.gif';
				if (formObj.img_tb) {
					formObj.img_tb.src = 'images/img_tb_off.gif';	
				}		
				return false;
			}
			formObj.img_at.src = 'images/img_at.gif';
			if (formObj.img_tb) {
				formObj.img_tb.src = 'images/img_tb.gif';
			}
			return true;
		} else { // standalone mode
			formObj.img_at.src = 'images/img_at_off.gif';
			formObj.img_po.src = 'images/img_po_off.gif';			
			if (cfile == '') {				
				if (formObj.img_tb) {
					formObj.img_tb.src = 'images/img_tb_off.gif';	
				}		
				return false;
			}
			if (formObj.img_tb) {
				formObj.img_tb.src = 'images/img_tb.gif';
				return true;
			}			
		}
	}	
// ============================================================
// = show about, date: 06/04/2005                             =
// ============================================================	
	function about() {		
		var formObj = document.forms[0];	
		if (document.getElementById('imDiv').className == 'hideit') {
			var x = document.getElementById('menuBarDiv').getElementsByTagName('li');
			for (var i = 0; i < x.length; i++) {
				if (x[i].className == 'btnDown') {				
					formObj.param.value = (x[i].id);
					elm = x[i].id.substring(x[i].id.length-2, x[i].id.length);			
					if (elm == 'po') { // popup windows - uses inDiv
						elm = 'in'
					}
					elm = elm + 'Div';
					document.getElementById('mainDivHeader').innerHTML = setTitle('imDiv'); 		
					changeClass(1,elm,'hideit','imDiv','showit');											
				}
			}
		} else if (document.getElementById('imDiv').className == 'showit' && formObj.param.value != '') {
			elm = formObj.param.value;			
			btn_click('menuBarDiv',elm);			
		}
	}
// ============================================================
// = show/hide load message, date: 07/07/2005                 =
// ============================================================
	function showloadmessage() {
		document.getElementById('dialogLoadMessage').style.display = 'block';
	}	
	function hideloadmessage() {
		document.getElementById('dialogLoadMessage').style.display = 'none';
	}	
// ============================================================
// = set manual crop div, date: 02/08/2005                    =
// ============================================================
	function setCrm() {	
		var formObj  = document.forms[0];	
		var cwidth   = document.getElementById('cimg').attributes['cwidth'].value;			// get current width	
		var cheight  = document.getElementById('cimg').attributes['cheight'].value;			// get current height	
		
		var sizes  = resizePreview(cwidth,cheight,80,60);
		var mh = sizes['h']; 	// max height
		var mw = sizes['w']; 	// max width		
		var m  = cheight/mh 	// source height to max height;	
		document.getElementById('crmWrap').style.width 		= mw;
		document.getElementById('crmWrap').style.height 	= mh +2;
		document.getElementById('crmDiv').style.width 		= formObj.cr_width.value/m;
		document.getElementById('crmDiv').style.height 		= formObj.cr_height.value/m;
		document.getElementById('crmDiv').style.marginLeft 	= formObj.cr_left.value/m;
		document.getElementById('crmDiv').style.marginTop 	= formObj.cr_top.value/m;	
	} 
// ============================================================
// = show examples, date: 04/07/2005                          =
// ============================================================	
	function sExamples() {
		var wArgs = {}
		wArgs.iManager = iManager;				
		if (iManager.isMSIE) { 
			var wnd = showModalDialog('images/examples/examples.php?lang=<?php echo $l->lang; ?>', wArgs, 
			'dialogHeight:310px; dialogWidth:420px; scrollbars: no; menubar: no; toolbar: no; resizable: no; status: no;');				
		} else if (iManager.isGecko) { 
			var wnd = window.open('images/examples/examples.php?lang=<?php echo $l->lang; ?>', 'examples', 'status=no, modal=yes, width=420, height=310'); 
			wnd.dialogArguments = wArgs;
		}
	}
// ============================================================
// = show image info layer, date: 08/06/2005                  =
// ============================================================
	function showInfo() {
		if (document.getElementById('cimg').attributes['cfile'].value != '') {			
			var obj  = document.getElementById('inPrevDiv');
			var oDiv = document.getElementById('infoDiv');				
			
			if (oDiv.className == 'showit') {
				changeClass(0,oDiv.id,'hideit');
			} else {
				document.getElementById('inf_cwidth').innerHTML  = document.getElementById('cimg').attributes['cwidth'].value  + ' px';
				document.getElementById('inf_cheight').innerHTML = document.getElementById('cimg').attributes['cheight'].value + ' px';
				ctype = document.getElementById('cimg').attributes['ctype'].value.split('|'); 
				document.getElementById('inf_ctype').innerHTML   = ctype[1];		
				csize = document.getElementById('cimg').attributes['csize'].value.split('|');
				document.getElementById('inf_csize').innerHTML   = csize[0] + ' ' + csize[1];				
				document.getElementById('inf_ccdate').innerHTML  = document.getElementById('cimg').attributes['ccdate'].value; 
				document.getElementById('inf_cmdate').innerHTML  = document.getElementById('cimg').attributes['cmdate'].value;		
				if (iManager.isMSIE) {
					moveInfoTo(obj, oDiv, 0, 0); // object to move to (destination), object being moved, x offset, y offset		
				} else if (iManager.isGecko) {
					moveInfoTo(obj, oDiv, 0, 0); // object to move to (destination), object being moved, x offset, y offset
				}						
				changeClass(0, oDiv.id, 'showit');
			}
		}	
	}
// ============================================================
// = move layer/div to object, date: 04/22/2005               =
// ============================================================
	function moveInfoTo(obj, oDiv, ox, oy) {			
			var newX = getPosX(obj) + ox;
			var newY = getPosY(obj) + oy;			
			document.getElementById(oDiv.id).style.left = newX + 'px';
			document.getElementById(oDiv.id).style.top  = newY + 'px';					
	}
// ============================================================
// = get object's position, date: 04/22/2005                  =
// ============================================================
	function getPosX(obj) { // get X position
		var cleft = 0;
		if (obj.offsetParent) {
			while (obj.offsetParent) {
				cleft += obj.offsetLeft
				obj    = obj.offsetParent;
			}
		} else if (obj.x) {
			cleft += obj.x;
		}
		return cleft;		
	}

	function getPosY(obj) { // get Y position
		var ctop = 0;
		if (obj.offsetParent) {
			while (obj.offsetParent) {
				ctop += obj.offsetTop
				obj   = obj.offsetParent;
			}
		} else if (obj.y) {
			ctop += obj.y;
		}
		return ctop;
	}
// ============================================================
// = returns absolute path, date: 04/22/2005                  =
// ============================================================
	function absPath(path) {
		if (path.charAt(0) != '/') {
			path = '/' + path;			
		}
		return path;
	}
// ============================================================
// = returns relative path, date: 04/22/2005                  =
// ============================================================
	function relPath(path) {
		if (path.charAt(0) == '/') {
			path = path.substring(1);			
		}
		return path;
	}
// ============================================================
// = show/hide quality on format change, date: 04/29/2005     =
// ============================================================
	function oFormatChange(obj) {		
		changeClass(0,'oQualityDiv','hideit'); // hide quality select box per default		
		if (obj.value == 2) { // if jpeg selected - show quality select box
			changeClass(0,'oQualityDiv','showit');
		}		
	}
// ============================================================
// = show/hide quality on format change, date: 04/29/2005     =
// ============================================================
	function oFileChange(obj) {		
		if (obj.checked) { // if oFile checked - show oNewFileDiv (overwrite)			
			var cfile = document.getElementById('cimg').attributes['cfile'].value;	// get current image			
			changeClass(0,'oNewFileDiv','showit');			
		} else {
			changeClass(0,'oNewFileDiv','hideit');
		}		
	}
//-->
</script>
</head>
<body onLoad="init(); hideloadmessage();" dir="<?php echo $l->getDir(); ?>">
<?php include dirname(__FILE__) . '/scripts/loadmsg.php'; ?>
<!- image info layer (cimg) -->
<div id="infoDiv" class="hideit">
  <div>
    <label><?php echo $l->m('in_028'); ?>:</label>
    <span id="inf_cwidth"> </span>
  </div>
  <div>
    <label><?php echo $l->m('in_029'); ?>:</label>
    <span id="inf_cheight"> </span>
  </div>
  <div>
    <label><?php echo $l->m('in_030'); ?>:</label>
    <span id="inf_ctype"> </span>
  </div>
  <div>
    <label><?php echo $l->m('in_031'); ?>:</label>
    <span id="inf_csize"> </span>
  </div>
  <div>
    <label><?php echo $l->m('in_033'); ?>:</label>
    <span id="inf_ccdate"> </span>
  </div>
  <div>
    <label><?php echo $l->m('in_034'); ?>:</label>
    <span id="inf_cmdate"> </span>
  </div>
</div>
<form id="iManager" name="iManager" method="post" action="scripts/rfiles.php" enctype="multipart/form-data" target="inSelFrame">
  <input type="hidden" name="lang" value="<?php echo $l->lang; ?>" />
  <input type="hidden" id="param" name="param" value="" />
  <input type="hidden" id="flist" name="flist" value="<?php echo $cfg['list']; ?>" />
  <input type="hidden" id="cimg" name="cimg" value="" cfile="" cwidth="" cheight="" csize="" ctype="" ccdate="" cmdate="" />
  <input type="hidden" name="cropstat" value="" />
  <div id="outerDivWrap">
    <div class="headerDiv">
      <div class="btnRight">
        <img src="images/about_off.gif" alt="<?php echo $l->m('im_015'); ?>" width="16" height="16" border="0" align="middle" title="<?php echo $l->m('im_015'); ?>" onClick="about();" onMouseOver="this.src='images/about.gif';" onMouseOut="this.src='images/about_off.gif';" />
      </div>
      <?php echo $l->m('im_002'); ?>
    </div>
    <div class="brdPad">
      <!- MAIN MENU --------------------------------------------------------- -->
      <div id="menuDivWrap">
        <div class="headerDiv">
          <?php echo $l->m('im_003'); ?>
        </div>
        <div class="brdPad">
          <div id="menuDiv">
            <div id="menuBarDiv">
              <ul>
                <li id="mbtn_in" class="btnUp"><img id="img_in" src="images/img_in.gif" width="40" height="40" />
                  <div>
                    <?php echo $l->m('im_007'); ?>
                  </div>
                </li>
                <li id="mbtn_at" class="btnUp"><img id="img_at" src="images/img_at.gif" width="40" height="40" />
                  <div>
                    <?php echo $l->m('im_009'); ?>
                  </div>
                </li>
                <li id="mbtn_tb" class="btnUp"><img id="img_tb" src="images/img_tb.gif" width="40" height="40" />
                  <div>
                    <?php echo $l->m('im_011'); ?>
                  </div>
                </li>
                <li id="mbtn_po" class="btnUp"><img id="img_po" src="images/img_po.gif" width="40" height="40" />
                  <div>
                    <?php echo $l->m('im_013'); ?>
                  </div>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!- // menuDivWrap -->
      <!- MAIN CONTENT ------------------------------------------------------ -->
      <div id="mainDivWrap">
        <div id="mainDivHeader" class="headerDiv">
          <?php echo $l->m('im_016'); ?>
        </div>
        <div class="brdPad">
          <div id="mainDiv">
            <!- WELCOME ----------------------------------------------------------- -->
            <div id="imDiv" class="showit">
              <div class="btnRight">
                <img onClick="sExamples();" src="images/im.gif" alt="<?php echo $l->m('im_001'); ?>" title="<?php echo $l->m('im_001'); ?>" width="48" height="48" border="0" />
              </div>
              <p><strong>net<span class="hilight">4</span>visions.com</strong> - the image manager for WYSIWYG editors like FCKeditor, SPAW, tinyMCE, and Xinha!</p>
              <p>Not only does <strong> <span class="hilight">i</span>Manager</strong> upload images and supply file management functions, it also adds truecolor image editing functions like: resize, flip, crop, add text, gamma correct, merge into other image, and many others.</p>
              <p><strong> <span class="hilight">i</span>Manager</strong> is written and distributed under the Lesser General Public License LGPL which means that its source code is freely-distributed and available to the general public for <strong>non</strong>-commercial use.</p>
              <br />
              <p>Click <a href="javascript:void(0);" onClick="sExamples(); return false;">here</a> for some examples.</p>
              <div>
                <div class="btnRight">
                  <img src="images/firefox.gif" alt="" title="" width="80" height="15" align="absmiddle" /><img src="images/explorer.gif" alt="" title="" width="80" height="15" align="absmiddle" />
                </div>
                <span class="ver">Version: <?php echo $cfg['ver']; ?></span>
              </div>
            </div>
            <!- // imDiv -->
            <!- INSERT/CHANGE ----------------------------------------------------- -->
            <div id="inDiv" class="hideit">
              <fieldset>
              <!- select library ---------------------------------------------------- -->
              <div id="ilibsDiv" class="showit">
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('in_003'); ?>" alt="<?php echo $l->m('in_003'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="ilibs"> <span class="title"> <?php echo $l->m('in_002'); ?> </span> </label>
                  <select class="fldlg" id="ilibs" name="ilibs" size="1" onChange="ilibsClick(this);">
                    <?php echo $lib_options; ?>
                  </select>
                </div>
              </div>
              </fieldset>
              <div class="floatWrap">
                <!- left column ------------------------------------------------------- -->
                <div class="colLeft">
                  <div style="float:left;">
                    <!- select image ------------------------------------------------------ -->
                    <div class="rowDiv">
                      <label> <span class="title"> <?php echo $l->m('in_004'); ?> </span> </label>
                    </div>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <span><img src="images/info_off.gif" onMouseOver="this.src='images/info.gif'; showInfo();" onMouseOut="this.src='images/info_off.gif'; showInfo();" alt="<?php echo $l->m('in_035'); ?>" title="<?php echo $l->m('in_035'); ?>" width="16" height="16" border="0" /><br />
                        <img src="images/dirview_off.gif" onClick="switchList();" onMouseOver="this.src='images/dirview.gif';" onMouseOut="this.src='images/dirview_off.gif';" alt="<?php echo $l->m('in_037'); ?>" title="<?php echo $l->m('in_037'); ?>" width="16" height="16" border="0" /></span>
                      </div>
                      <div id="inSelDiv">
                        <iframe name="inSelFrame" id="inSelFrame" src="scripts/rfiles.php?clib=<?php echo $clib; ?>" style="width: 100%; height: 100%;" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"></iframe>
                      </div>
                    </div>
                  </div>
                </div>
                <!- // colLeft -->
                <!- right column ----------------------------------------------------- -->
                <div class="colRight">
                  <div style="float:left;">
                    <!- preview image ---------------------------------------------------- -->
                    <div class="rowDiv">
                      <label> <span class="title"> <?php echo $l->m('in_005'); ?> </span> </label>
                    </div>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="fullSizeView('in'); return false;" src="images/prev_off.gif" onMouseOver="this.src='images/prev.gif';" onMouseOut="this.src='images/prev_off.gif';" alt="<?php echo $l->m('in_007'); ?>" title="<?php echo $l->m('in_007'); ?>" width="16" height="16" border="0" />
                      </div>
                      <div id="inPrevDiv">
                        <iframe name="inPrevFrame" id="inPrevFrame" src="images/noImg.gif" style="width: 100%; height: 100%;" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"></iframe>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!- // colRight -->
              <!- popup section ---------------------------------------------------- -->
              <div id="poDiv" class="hideit">
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('in_013'); ?>" title="<?php echo $l->m('in_013'); ?>" width="16" height="16" border="0" />
                  </div>
                  <div class="poPrevDiv">
                    <iframe id="poPrevFrame" name="poPrevFrame" src="images/noPop.gif" style="width: 100%; height: 100%;" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"></iframe>
                  </div>
                  <label> <span class="title"> <?php echo $l->m('in_010'); ?> </span> </label>
                </div>
                <div class="rowDiv">
                  <div id="poDelDiv">
                    <label for="chkP"> <span class="pad10"> <?php echo $l->m('in_024'); ?> </span> </label>
                    <input type="checkbox" name="chkP" value="" class="chkBox"/>
                    <span class="frmText"> (<?php echo $l->m('in_014'); ?>) </span>
                  </div>
                </div>
                <div class="rowDiv">
                  <label for="popClassName"> <span class="pad10"> <?php echo $l->m('at_009'); ?> </span> </label>
                  <select class="fldm" id="popClassName" name="popClassName" />
                  
                  <option value="default" selected="selected"><?php echo $l->m('at_099'); ?></option>
                  <?php echo getStyles(false); ?>
                  </select>
                </div>
                <!- clear floats ------------------------------------------------------ -->
                <div class="clrFloatRight">
                </div>
                <div class="rowDiv">
                  <label for="popSrc"> <span class="pad10"> <?php echo $l->m('at_002'); ?> </span> </label>
                  <input class="fldlg readonly" id="popSrc" name="popSrc" type="text" value="" disabled="true" readonly="true" />
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="selSymbol('popTitle');" src="images/symbols_off.gif" onMouseOver="this.src='images/symbols.gif';" onMouseOut="this.src='images/symbols_off.gif';" title="<?php echo $l->m('at_029'); ?>" alt="<?php echo $l->m('at_029'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_004'); ?>" title="<?php echo $l->m('at_004'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="popTitle"> <span class="pad10"> <?php echo $l->m('at_003'); ?> </span> </label>
                  <input class="fldmlg" id="popTitle" name="popTitle" type="text" value="" />
                </div>
              </div>
              <!- // poDiv -->
              <!- file section ----------------------------------------------------- -->
              <div id="fileDivWrap" class="showit">
                <div class="rowDiv">
                  <div class="btnRight">
                    <?php if ($cfg['create'] && isset($cfg['ilibs_inc'])) {; ?>
                    <img src="images/dir_off.gif" onClick="changeClass(0,'fileDiv','showit','fiDirDiv','showit','fiUplDiv','hideit','fiRenDiv','hideit','fiDelDiv','hideit');" onMouseOver="this.src='images/dir.gif';" onMouseOut="this.src='images/dir_off.gif';" alt="<?php echo $l->m('in_027'); ?>" title="<?php echo $l->m('in_027'); ?>" width="16" height="16" />
                    <?php }; ?>
                    <?php if ($cfg['upload']) {; ?>
                    <img src="images/upimg_off.gif" onClick="changeClass(1,'fileDiv','showit','fiDirDiv','hideit','fiUplDiv','showit','fiRenDiv','hideit','fiDelDiv','hideit');" onMouseOver="this.src='images/upimg.gif';" onMouseOut="this.src='images/upimg_off.gif';" alt="<?php echo $l->m('in_019'); ?>" title="<?php echo $l->m('in_019'); ?>" width="16" height="16" />
                    <?php }; ?>
                    <?php if ($cfg['rename']) {; ?>
                    <img class="isecbtn"src="images/renimg_off.gif" onClick="changeClass(0,'fileDiv','showit','fiDirDiv','hideit','fiRenDiv','showit','fiUplDiv','hideit','fiDelDiv','hideit');" onMouseOver="this.src='images/renimg.gif';" onMouseOut="this.src='images/renimg_off.gif';" alt="<?php echo $l->m('in_017'); ?>" title="<?php echo $l->m('in_017'); ?>" width="16" height="16" border="0" />
                    <?php }; ?>
                    <?php if ($cfg['delete']) {; ?>
                    <img src="images/delimg_off.gif" onClick="changeClass(0,'fileDiv','showit','fiDirDiv','hideit','fiDelDiv','showit','fiRenDiv','hideit','fiUplDiv','hideit');" onMouseOver="this.src='images/delimg.gif';" onMouseOut="this.src='images/delimg_off.gif';" alt="<?php echo $l->m('in_006'); ?>" title="<?php echo $l->m('in_006'); ?>" width="16" height="16" border="0" />
                    <?php }; ?>
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('in_008'); ?>" title="<?php echo $l->m('in_008'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label> <span class="title"> <?php echo $l->m('in_015'); ?> </span> </label>
                </div>
                <!- clear floats ------------------------------------------------------ -->
                <div class="clrFloatRight">
                </div>
                <div id="fileDiv" class="showit">
                  <?php if ($cfg['delete']) { ?>
                  <div id="fiDelDiv" class="hideit">
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="deleteClick();" src="images/okclick_off.gif" onMouseOver="this.src='images/okclick.gif';" onMouseOut="this.src='images/okclick_off.gif';" alt="<?php echo $l->m('in_006'); ?>" title="<?php echo $l->m('in_006'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="in_srcnew"> <span class="pad10"> <?php echo $l->m('in_024'); ?> </span> </label>
                      <input class="fldlg readonly" id="in_delinfo" name="in_delinfo" type="text" value="" disabled="true" readonly="true" />
                    </div>
                  </div>
                  <?php }; ?>
                  <?php if ($cfg['rename']) { ?>
                  <div id="fiRenDiv" class="hideit">
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="renameClick();" src="images/okclick_off.gif" onMouseOver="this.src='images/okclick.gif';" onMouseOut="this.src='images/okclick_off.gif';" alt="<?php echo $l->m('in_017'); ?>" title="<?php echo $l->m('in_017'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="in_srcnew"> <span class="pad10"> <?php echo $l->m('in_016'); ?> </span> </label>
                      <input class="fldlg" id="in_srcnew" name="in_srcnew" type="text" value="" onKeyUp="RemoveInvalidChars(this, '[^A-Za-z0-9 \_-]'); ForceLowercase(this); CharacterReplace(this, ' ', '_'); return false;"  />
                    </div>
                  </div>
                  <?php }; ?>
                  <?php if ($cfg['create']) { ?>
                  <div id="fiDirDiv" class="hideit">
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="createClick();" src="images/okclick_off.gif" onMouseOver="this.src='images/okclick.gif';" onMouseOut="this.src='images/okclick_off.gif';" alt="<?php echo $l->m('in_026'); ?>" title="<?php echo $l->m('in_026'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="in_srcnew"> <span class="pad10"> <?php echo $l->m('in_025'); ?> </span> </label>
                      <input class="fldlg" id="in_dirnew" name="in_dirnew" type="text" value="" onKeyUp="RemoveInvalidChars(this, '[^A-Za-z0-9 \_-]'); ForceLowercase(this); CharacterReplace(this, ' ', '_'); return false;" />
                    </div>
                  </div>
                  <?php }; ?>
                  <?php if ($cfg['upload']) {; ?>
                  <div id="fiUplDiv" class="hideit">
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="uploadClick();" src="images/okclick_off.gif" onMouseOver="this.src='images/okclick.gif';" onMouseOut="this.src='images/okclick_off.gif';" alt="<?php echo $l->m('in_019'); ?>" title="<?php echo $l->m('in_019'); ?>" width="16" height="16" />
                      </div>
                      <?php 
							$max = isset($cfg['umax']) && $cfg['umax'] >= 1 ? $cfg['umax'] : 1;					
							for($i=1; $i <= $max; $i++) {; ?>
                      <label for="nfile"> <span class="pad10"> <?php echo $l->m('in_018'); if ($max > 1){ echo ' (' . $i . ')';} ?> </span> </label>
                      <input name="nfile[]" type="file" class="fldlg" id="nfile[]" size="53" accept="image/*" />
                      <?php }; ?>
                    </div>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('in_021'); ?>" alt="<?php echo $l->m('in_021'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="chkThumbSize[]"> <span class="pad20"> <?php echo $l->m('in_020'); ?> </span> </label>
                      <div id="fmtDiv">
                        <?php echo thumbSizes($cfg['thumbs']); ?>
                      </div>
                    </div>
                  </div>
                  <?php }; ?>
                </div>
              </div>
              <!- // fiDiv -->
            </div>
            <!- // inDiv -->
            <!- ATTRIBUTES -------------------------------------------------------- -->
            <div id="atDiv" class="hideit">
              <div class="floatWrap">
                <div class="rowDiv">
                  <label for="pr_src"> <span class="title"> <?php echo $l->m('at_002'); ?> </span> </label>
                  <input class="fldlg readonly" id="pr_src" name="pr_src" type="text" value="" disabled="true" readonly="true" />
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="selSymbol('pr_title');" src="images/symbols_off.gif" onMouseOver="this.src='images/symbols.gif';" onMouseOut="this.src='images/symbols_off.gif';" title="<?php echo $l->m('at_029'); ?>" alt="<?php echo $l->m('at_029'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_004'); ?>" title="<?php echo $l->m('at_004'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="pr_title"> <span class="title"> <?php echo $l->m('at_003'); ?> </span> </label>
                  <input class="fldmlg" id="pr_title" name="pr_title" type="text" value="" onChange="updateStyle()" />
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="selSymbol('pr_alt');" src="images/symbols_off.gif" onMouseOver="this.src='images/symbols.gif';" onMouseOut="this.src='images/symbols_off.gif';" title="<?php echo $l->m('at_030'); ?>" alt="<?php echo $l->m('at_030'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_006'); ?>" title="<?php echo $l->m('at_006'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="pr_alt"> <span class="title"> <?php echo $l->m('at_005'); ?> </span> </label>
                  <input class="fldmlg" id="pr_alt" name="pr_alt" type="text" value="" onChange="updateStyle()" />
                </div>
                <!- left column ------------------------------------------------------ -->
                <div class="colLeft">
                  <div class="rowDiv">
                    <label> <span class="title"> <?php echo $l->m('at_007'); ?> </span> </label>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_008'); ?>" title="<?php echo $l->m('at_008'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="pr_class"> <span class="pad10"> <?php echo $l->m('at_009'); ?> </span> </label>
                    <select class="fldm" id="pr_class" name="pr_class" onChange="updateStyle()">
                      <option value="default" selected="selected"><?php echo $l->m('at_099'); ?></option>
                      <?php echo getStyles(false); ?>
                    </select>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('at_011'); ?>" alt="<?php echo $l->m('at_011'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label> <span class="title"> <?php echo $l->m('at_010'); ?> </span> </label>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_align"> <span class="pad10"> <?php echo $l->m('at_012'); ?> </span> </label>
                    <select class="fldm" id="pr_align" name="pr_align" onChange="updateStyle()">
                      <option value=""><?php echo $l->m('at_013'); ?></option>
                      <option value="left"><?php echo $l->m('at_014'); ?></option>
                      <option value="right"><?php echo $l->m('at_015'); ?></option>
                      <option value="top"><?php echo $l->m('at_016'); ?></option>
                      <option value="middle"><?php echo $l->m('at_017'); ?></option>
                      <option value="bottom"><?php echo $l->m('at_018'); ?></option>
                    </select>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_size"> <span class="pad10"> <?php echo $l->m('at_022'); ?> </span> </label>
                    <input class="fldsm readonly" id="pr_size" name="pr_size" type="text"value="" maxlength="8" disabled="true" readonly="true" />
                    <span class="frmText">(<span id="pr_sizeUnit"></span>)</span>
                  </div>
                  <div class="rowDiv">
                    <?php if ($cfg['attrib'] == true) {; ?>
                    <div class="btnRight">
                      <img src="images/img_size_off.gif" onMouseOver="this.src='images/img_size.gif';" onMouseOut="this.src='images/img_size_off.gif';" onClick="resetDim();" alt="<?php echo $l->m('at_031'); ?>" title="<?php echo $l->m('at_031'); ?>" width="16" height="16" border="0" />
                    </div>
                    <?php }; ?>
                    <label for="pr_width"> <span class="pad10"> <?php echo $l->m('at_023'); ?> </span> </label>
                    <input id="pr_width" name="pr_width" type="text"value="" maxlength="4" <?php if ($cfg['attrib'] != true) {; ?> class="fldsm readonly" disabled="true" readonly="true" <?php } else {; ?> class="fldsm" onchange="changeDim(0);" onkeyup="RemoveInvalidChars(this, '[^0-9]');" <?php }; ?> />
                    <span class="frmText"> (px) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_height"> <span class="pad10"> <?php echo $l->m('at_024'); ?> </span> </label>
                    <input id="pr_height" name="pr_height" type="text"value="" maxlength="4" <?php if ($cfg['attrib'] != true) {; ?> class="fldsm readonly" disabled="true" readonly="true" <?php } else {; ?> class="fldsm" onchange="changeDim(1);" onkeyup="RemoveInvalidChars(this, '[^0-9]');" <?php }; ?> />
                    <span class="frmText"> (px) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_border"> <span class="pad10"> <?php echo $l->m('at_025'); ?> </span> </label>
                    <input class="fldsm" id="pr_border" name="pr_border" type="text"value="" maxlength="2" onChange="updateStyle();" onKeyUp="RemoveInvalidChars(this, '[^0-9]');"  />
                    <span class="frmText"> (px) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_vspace"> <span class="pad10"> <?php echo $l->m('at_026'); ?> </span> </label>
                    <input class="fldsm" id="pr_vspace" name="pr_vspace" type="text" value="" maxlength="2" onChange="updateStyle();" onKeyUp="RemoveInvalidChars(this, '[^0-9]');" />
                    <span class="frmText"> (px) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="pr_hspace"> <span class="pad10"> <?php echo $l->m('at_027'); ?> </span> </label>
                    <input class="fldsm" id="pr_hspace" name="pr_hspace" type="text" value="" maxlength="2" onChange="updateStyle();" onKeyUp="RemoveInvalidChars(this, '[^0-9]');" />
                    <span class="frmText"> (px) </span>
                  </div>
                </div>
                <!- // colLeft -->
                <!- right column ----------------------------------------------------- -->
                <div class="colRight">
                  <div style="float:left;">
                    <div class="rowDiv">
                      <label> <span class="title"> <?php echo $l->m('at_028'); ?> </span> </label>
                    </div>
                    <div id="atPrevDiv">
                      <p><img id="atPrevImg" src="images/textflow.gif" width="45" height="45" alt="" title="" hspace="" vspace="" border="" class="" />Lorem ipsum, Dolor sit amet, consectetuer adipiscing loreum ipsum edipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.Loreum ipsum edipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat. Ut wisi enim ad minim veniam, quis nostrud exercitation ullamcorper suscipit. Lorem ipsum, Dolor sit amet, consectetuer adipiscing loreum ipsum edipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.</p>
                    </div>
                  </div>
                </div>
                <!- // colRight -->
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_033'); ?>" title="<?php echo $l->m('at_033'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="pr_chkCaption"> <span class="title"> <?php echo $l->m('at_032'); ?> </span> </label>
                  <input name="pr_chkCaption" type="checkbox" class="chkBox" id="pr_chkCaption" onChange="updateStyle()" value="1" />
                  <span class="frmText">(<?php echo $l->m('at_034'); ?>)</span>
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('at_008'); ?>" title="<?php echo $l->m('at_008'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="pr_captionClass"> <span class="pad10"> <?php echo $l->m('at_009'); ?> </span> </label>
                  <select class="fldm" id="pr_captionClass" name="pr_captionClass" onChange="updateStyle()">
                    <option value="default" selected="selected"><?php echo $l->m('at_099'); ?></option>
                    <?php echo getStyles(true); ?>
                  </select>
                </div>
              </div>
            </div>
            <!- // atDiv -->
            <!- TOOLBOX ----------------------------------------------------------- -->
            <div id="tbDiv" class="hideit">
              <div id="ouDiv" class="showit">
                <!- toolbox submenu --------------------------------------------------- -->
                <div id="subMenuBarDiv">
                  <ul>
                    <li class="btnUp" id="btn_ou"><img src="images/img_asm.gif" alt="<?php echo $l->m('tb_001'); ?>" title="<?php echo $l->m('tb_001'); ?>" width="32" height="32" /></li>
                    <li class="btnUp" id="btn_rs"><img src="images/img_res.gif" alt="<?php echo $l->m('tb_002'); ?>" title="<?php echo $l->m('tb_002'); ?>" width="32" height="32" /></li>
                    <li class="btnUp" id="btn_cr"><img src="images/img_crop.gif" alt="<?php echo $l->m('tb_003'); ?>" title="<?php echo $l->m('tb_003'); ?>" width="32" height="32" /></li>
                    <li class="btnUp" id="btn_or"><img src="images/img_flip.gif" alt="<?php echo $l->m('tb_004'); ?>" title="<?php echo $l->m('tb_004'); ?>" width="32" height="32" /></li>
                    <li class="btnUp" id="btn_fi"><img src="images/img_col.gif" alt="<?php echo $l->m('tb_005'); ?>" title="<?php echo $l->m('tb_005'); ?>" width="32" height="32" /></li>
                    <li class="btnUp" id="btn_wm"><img src="images/img_wm.gif" alt="<?php echo $l->m('tb_007'); ?>" title="<?php echo $l->m('tb_007'); ?>" width="32" height="32" /></li>
                    <li class="btnUp" id="btn_ov"><img src="images/img_ovl.gif" alt="<?php echo $l->m('tb_007'); ?>" title="<?php echo $l->m('tb_026'); ?>" width="32" height="32" /></li>
                    <li class="btnUp" id="btn_ms"><img src="images/img_mask.gif" alt="<?php echo $l->m('tb_008'); ?>" title="<?php echo $l->m('tb_008'); ?>" width="32" height="32" /></li>
                    <li class="btnUp" id="btn_wz"><img src="images/img_wiz.gif" alt="<?php echo $l->m('tb_009'); ?>" title="<?php echo $l->m('tb_009'); ?>" width="32" height="32" /></li>
                    <li class="btnUp" id="btn_se"><img src="images/img_inf.gif" alt="<?php echo $l->m('tb_010'); ?>" title="<?php echo $l->m('tb_010'); ?>" width="32" height="32" /></li>
                  </ul>
                </div>
                <!- // subMenuBarDiv -->
                <div class="rowDiv">
                  <label for="tb_src"> <span class="title"> <?php echo $l->m('tb_011'); ?> </span> </label>
                  <input class="fldlg readonly" id="tb_src" name="tb_src" type="text" size="10" disabled="true" readonly="true" />
                </div>
                <div class="rowDiv">
                  <label for="tb_info"> <span class="title"> <?php echo $l->m('tb_012'); ?> </span> </label>
                  <input class="fldlg readonly" id="tb_info" name="tb_info" type="text" size="10" disabled="true" readonly="true" />
                </div>
                <div class="floatWrap">
                  <!- left column ------------------------------------------------------ -->
                  <div class="colLeft">
                    <!- output settings ------------------------------------------------- -->
                    <div class="rowDiv">
                      <label> <span class="title"> <?php echo $l->m('tb_013'); ?> </span> </label>
                    </div>
                    <?php if ($mode != 2) {; // only if not standalone ?>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('tb_015'); ?>" alt="<?php echo $l->m('tb_015'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label> <span class="pad10"> <?php echo $l->m('tb_014'); ?> </span> </label>
                      <input onClick="oFileChange(this);" id="chk_oFile" name="chk_oFile" type="checkbox" value="" class="chkBox" checked="checked" />
                    </div>
                    <div class="clrFloat">
                    </div>
                    <div id="oNewFileDiv" class="showit">
                      <div class="rowDiv">
                        <div class="btnRight">
                          <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('tb_017'); ?>" alt="<?php echo $l->m('tb_017'); ?>" width="16" height="16" border="0" />
                        </div>
                        <label> <span class="pad20"> <?php echo $l->m('tb_016'); ?> </span> </label>
                        <input id="chk_oNewFile" name="chk_oNewFile" type="checkbox" value="" class="chkBox" checked="checked" />
                      </div>
                    </div>
                    <?php }; ?>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('tb_019'); ?>" title="<?php echo $l->m('tb_019'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="sel_oFormat"> <span class="pad20"> <?php echo $l->m('tb_018'); ?> </span> </label>
                      <select class="fldm" id="sel_oFormat" name="sel_oFormat" size="1" onChange="oFormatChange(this);">
                        <option value="2" selected="selected">jpeg</option>
                        <option value="3">png</option>
                        <option value="1">gif</option>
                      </select>
                    </div>
                    <div id="oQualityDiv" class="showit">
                      <div class="rowDiv">
                        <label for="sel_oQuality"> <span class="pad30"> <?php echo $l->m('tb_020'); ?> </span> </label>
                        <select class="fldm" id="sel_oQuality" name="sel_oQuality" size="1">
                          <?php echo writeOptions(5,95,5,1,'&#37',85); ?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <!- // colLeft -->
                  <!- right column ----------------------------------------------------- -->
                  <div class="colRight">
                    <div style="float: left;">
                      <!- preview image ---------------------------------------------------- -->
                      <div class="rowDiv">
                        <label> <span class="title"> <?php echo $l->m('tb_024'); ?> </span> </label>
                      </div>
                      <div class="rowDiv">
                        <div class="btnRight">
                          <img onClick="fullSizeView('tb'); return false;" src="images/prev_off.gif" onMouseOver="this.src='images/prev.gif';" onMouseOut="this.src='images/prev_off.gif';" alt="<?php echo $l->m('in_007'); ?>" title="<?php echo $l->m('in_007'); ?>" width="16" height="16" border="0" />
                        </div>
                        <div id="tbPrevDiv">
                          <iframe name="tbPrevFrame" id="tbPrevFrame" src="images/noImg.gif" style="width: 100%; height: 100%;" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"></iframe>
                        </div>
                      </div>
                      <div id="moDiv">
                        <div>
                          <input onClick="updatePreview(0);" id="refresh" name="refresh" type="button" class="btn" title="<?php echo $l->m('tb_096'); ?>" value="<?php echo $l->m('tb_098'); ?>" />
                          <span class="pad5">
                          <input onClick="updatePreview(2);" id="accept" name="accept" type="button" class="btn" title="<?php echo $l->m('tb_097'); ?>" value="<?php echo $l->m('tb_099'); ?>" />
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <!- // colRight -->
                </div>
              </div>
              <!- // ouDiv -->
              <!- crop interface --------------------------------------------------- -->
              <div id="ciDiv" class="hideit">
                <div id="crPrevDiv">
                  <div class="btnRight">
                    <img onClick="cropIt(0)" src="images/reload_off.gif" onMouseOver="this.src='images/reload.gif';" onMouseOut="this.src='images/reload_off.gif';" alt="<?php echo $l->m('ci_003'); ?>" title="<?php echo $l->m('ci_003'); ?>" width="16" height="16"  /><img  id="cr_ibtn" name="cr_ibtn" onClick="cropIt(1);" src="images/crop_off.gif" onMouseOver="this.src='images/crop.gif';" onMouseOut="this.src='images/crop_off.gif';" alt="<?php echo $l->m('ci_004'); ?>" title="<?php echo $l->m('ci_004'); ?>" width="16" height="16"   /><img  onClick="cropIt(2)" src="images/close_off.gif" onMouseOver="this.src='images/close.gif';" onMouseOut="this.src='images/close_off.gif';" alt="<?php echo $l->m('ci_005'); ?>" title="<?php echo $l->m('ci_005'); ?>" width="16" height="16" />
                  </div>
                  <iframe name="crPrevFrame" id="crPrevFrame" src="images/noImg.gif" style="height: 300px; width: 400px;" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"></iframe>
                </div>
              </div>
              <!- // ciDiv -->
            </div>
            <!- // tbDiv -->
          </div>
        </div>
      </div>
      <!- // mainDivWrap -->
      <!- submain ---------------------------------------------------------- -->
      <div id="subMainDivWrap" class="hideit">
        <div id="subMainDivHeader" class="headerDiv">
          <?php echo $l->m('im_016'); ?>
        </div>
        <div class="brdPad">
          <div id="subMainDiv">
            <!- resize image ----------------------------------------------------- -->
            <div id="rsDiv" class="hideit">
              <div class="floatWrap">
                <div class="rowDiv">
                  <label for="chkResize"> <span class="title"> <?php echo $l->m('rs_002'); ?> </span> </label>
                  <input name="chkResize" type="checkbox" class="chkBox" id="chkResize" onClick="setchkBox('chkResize','chkResize')" value="" checked="checked" />
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img src="images/cona_off.gif" alt="<?php echo $l->m('rs_005'); ?>" name="irsreset" width="16" height="16" border="0" id="irsreset" style="cursor: pointer;" title="<?php echo $l->m('rs_005'); ?>" onClick="rschkDim(this);" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'cona_off.gif') { this.src='images/cona.gif' } else {this.src='images/coni.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'cona.gif') { this.src='images/cona_off.gif' } else {this.src='images/coni_off.gif'};" />
                  </div>
                </div>
                <div class="rowDiv">
                  <label for="rs_width"> <span class="pad10"> <?php echo $l->m('rs_003'); ?> </span> </label>
                  <input class="fldsm" id="rs_width" name="rs_width" type="text" value="" size="7" maxlength="4" onChange="rschkDim(this)" onKeyUp="RemoveInvalidChars(this, '[^0-9]');" />
                  <span class="frmText"> (px) </span>
                </div>
                <div class="rowDiv">
                  <label for="rs_height"> <span class="pad10"> <?php echo $l->m('rs_004'); ?> </span> </label>
                  <input class="fldsm" id="rs_height" name="rs_height" type="text" value="" size="7" maxlength="4" onChange="rschkDim(this)" onKeyUp="RemoveInvalidChars(this, '[^0-9]');" />
                  <span class="frmText"> (px) </span>
                </div>
                <!- allow enlargment -->
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('rs_022'); ?>" title="<?php echo $l->m('rs_022'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="rs_chkEnla"> <span class="pad10"> <?php echo $l->m('rs_020'); ?> </span> </label>
                  <input id="rs_chkEnla" name="rs_chkEnla" type="checkbox" class="chkBox" value="aoe" onClick="rschkDim(this);" />
                  <span class="frmText"> (<?php echo $l->m('rs_021'); ?>) </span>
                </div>
                <div class="rowDiv">
                  <label> <span class="pad10"> <span class="title"> <?php echo $l->m('rs_006'); ?> </span> </span> </label>
                </div>
                <!- keep aspect ratio -->
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('rs_008'); ?>" title="<?php echo $l->m('rs_008'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label> <span class="pad20"> <?php echo $l->m('rs_007'); ?> </span> </label>
                  <input name="rs_type" id="rs_type" type="radio" class="chkBox" value="" checked="checked" onClick="rschkDim(this); changeClass(1,'rsclDiv','hideit');"/>
                  <span class="frmText"> (<?php echo $l->m('rs_099'); ?>: <?php echo $l->m('rs_009'); ?>) </span>
                </div>
                <!- ignore aspect ratio -->
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('rs_011'); ?>" title="<?php echo $l->m('rs_011'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label> <span class="pad20"> <?php echo $l->m('rs_010'); ?> </span> </label>
                  <input name="rs_type" id="rs_type" type="radio" class="chkBox" value="iar" onClick="rschkDim(this); changeClass(1,'rsclDiv','hideit');" />
                  <span class="frmText"> (<?php echo $l->m('rs_012'); ?>) </span>
                </div>
                <!- force aspect ratio -->
                <div class="rowDiv">
                  <div class="btnRight">
                    <img id="rsclbtn0" onClick="changeClass(1,'rsclDiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img id="rsclbtn1" onClick="changeClass(1,'rsclDiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('rs_015'); ?>" alt="<?php echo $l->m('rs_015'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label> <span class="pad20"> <?php echo $l->m('rs_013'); ?> </span> </label>
                  <input id="rs_type" name="rs_type" type="radio" class="chkBox" value="far" onClick="rschkDim(this); changeClass(1,'rsclDiv','showit'); " />
                  <span class="frmText"> (<?php echo $l->m('rs_014'); ?>) </span>
                </div>
                <div class="clrFloat">
                </div>
                <div id="rsclDiv" class="hideit">
                  <div class="rowDiv">
                    <label for="rs0_col"> <span class="pad30"> <?php echo $l->m('rs_016'); ?> </span> </label>
                    <input class="fldsm" onChange="selColor(this);" type="text" id="rs0_col" name="rs0_col" size="7" value="#ffffff" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                    <input onClick="selColor(this);" class="fldCol" type="button" name="rs0_icol" id="rs0_icol" value="" style="background-color: #ffffff;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                    <span class="frmText"> (<?php echo $l->m('to_098'); ?>) </span>
                  </div>
                </div>
                <!- zoom crop -->
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" alt="<?php echo $l->m('rs_019'); ?>" title="<?php echo $l->m('rs_019'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label> <span class="pad20"> <?php echo $l->m('rs_017'); ?> </span> </label>
                  <input id="rs_type" name="rs_type" type="radio" class="chkBox" value="zc" onClick="rschkDim(this); changeClass(1,'rsclDiv','hideit');" />
                  <span class="frmText"> (<?php echo $l->m('rs_018'); ?>) </span>
                </div>
              </div>
            </div>
            <!- // rsDiv -->
            <!- crop image ------------------------------------------------------- -->
            <div id="crDiv" class="hideit">
              <div class="floatWrap">
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="cropIt(0);" src="images/ocrop_off.gif" onMouseOver="this.src='images/ocrop.gif';" onMouseOut="this.src='images/ocrop_off.gif';" alt="<?php echo $l->m('cr_003'); ?>" title="<?php echo $l->m('cr_003'); ?>" width="16" height="16"   /><img  onClick="cropIt(2);" src="images/ccrop_off.gif" onMouseOver="this.src='images/ccrop.gif';" onMouseOut="this.src='images/ccrop_off.gif';" alt="<?php echo $l->m('cr_004'); ?>" title="<?php echo $l->m('cr_004'); ?>" width="16" height="16" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('cr_005'); ?>" alt="<?php echo $l->m('cr_005'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="chkCrop"> <span class="title"> <?php echo $l->m('cr_002'); ?> </span> </label>
                  <input name="chkCrop" id="chkCrop" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkCrop','chkCrop');" />
                </div>
                <div class="rowDiv">
                  <div id="crmWrap">
                    <div id="crmDiv">
                      <img src="images/spacer.gif" alt="<?php echo $l->m('cr_003'); ?>" width="100%" height="100%" border="0" title="<?php echo $l->m('cr_003'); ?>" onClick="cropIt(0);">
                    </div>
                  </div>
                  <div class="rowDiv">
                    <label for="cr_width"> <span class="pad10"> <?php echo $l->m('cr_006'); ?> </span> </label>
                    <input class="fldsm" id="cr_width" name="cr_width" type="text" value="" size="7" maxlength="4" onKeyUp="RemoveInvalidChars(this, '[^0-9]');" onChange="crchkDim(this);" />
                    <span class="frmText"> (<?php echo $l->m('cr_099'); ?>: 50%) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="cr_height"> <span class="pad10"> <?php echo $l->m('cr_007'); ?> </span> </label>
                    <input class="fldsm" id="cr_height" name="cr_height" type="text" value="" size="7" maxlength="4" onKeyUp="RemoveInvalidChars(this, '[^0-9]');" onChange="crchkDim(this);" />
                    <span class="frmText"> (<?php echo $l->m('cr_099'); ?>: 50%) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="cr_top"> <span class="pad10"> <?php echo $l->m('cr_008'); ?> </span> </label>
                    <input class="fldsm" id="cr_top" name="cr_top" type="text" value="0" size="7" maxlength="4" onKeyUp="RemoveInvalidChars(this, '[^0-9]');" onChange="crchkDim(this);" />
                    <span class="frmText"> (<?php echo $l->m('cr_099'); ?>: 0px) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="cr_left"> <span class="pad10"> <?php echo $l->m('cr_009'); ?> </span> </label>
                    <input class="fldsm" id="cr_left" name="cr_left" type="text" value="0" size="7" maxlength="4" onKeyUp="RemoveInvalidChars(this, '[^0-9]');" onChange="crchkDim(this);" />
                    <span class="frmText"> (<?php echo $l->m('cr_099'); ?>: 0px) </span>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('cr_011'); ?>" alt="<?php echo $l->m('cr_011'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label> <span class="pad10"> <?php echo $l->m('cr_010'); ?> </span> </label>
                    <input name="cr_ratio" type="radio" class="chkBox" id="cr_ratio" onClick="crchkDim(this);" value="1" checked="checked" />
                    <span class="frmText"> 4:3 </span>
                    <input id="cr_ratio" name="cr_ratio" type="radio" value="1" class="chkBox" onClick="crchkDim(this);" />
                    <span class="frmText"> 1:1 </span>
                    <input id="cr_ratio" name="cr_ratio" type="radio" value="0" class="chkBox" onClick="crchkDim(this);" />
                    <span class="frmText"> <?php echo $l->m('cr_012'); ?> </span>
                  </div>
                </div>
              </div>
            </div>
            <!- // crDiv -->
            <!- orientation ------------------------------------------------------ -->
            <div id="orDiv" class="hideit">
              <div class="floatWrap">
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('or_003'); ?>" alt="<?php echo $l->m('or_003'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="chkOrientation"> <span class="title"> <?php echo $l->m('or_002'); ?> </span> </label>
                  <input name="chkOrientation" id="chkOrientation" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkOrientation','chkOrientation');" />
                </div>
                <div class="rowDiv">
                  <label for="chkFlip"> <span class="pad10"> <?php echo $l->m('or_004'); ?> </span> </label>
                  <input id="chkFlip" name="chkFlip" type="checkBox" value="0" class="chkBox" onClick="setchkBox('chkOrientation','chkFlip','chkRotate'); changeClass(1,'rocodiv','hideit');" />
                  <select class="fldm" id="or_flip" name="or_flip">
                    <option value="x" selected="selected"><?php echo $l->m('or_005'); ?></option>
                    <option value="y"><?php echo $l->m('or_006'); ?></option>
                    <option value="xy"><?php echo $l->m('or_007'); ?></option>
                  </select>
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="changeClass(1,'rocodiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'rocodiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('or_009'); ?>" alt="<?php echo $l->m('or_009'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="chkRotate"> <span class="pad10"> <?php echo $l->m('or_008'); ?> </span> </label>
                  <input name="chkRotate" id="chkRotate" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkOrientation','chkFlip','chkRotate'); changeClass(1,'rocodiv','showit');" />
                </div>
                <div class="clrFloat">
                </div>
                <div id="rocodiv" class="hideit">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('or_011'); ?>" alt="<?php echo $l->m('or_011'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="ro_type"> <span class="pad20"> <?php echo $l->m('or_010'); ?> </span> </label>
                    <input name="ro_type" type="radio" class="chkBox" value="0" checked="checked" />
                    <select class="fldm" id="ro_angle" name="ro_angle">
                      <?php echo writeOptions(-360,360,1,1,'&deg;',90); ?>
                    </select>
                    <span class="frmText"> (<?php echo $l->m('or_099'); ?>: 90&deg;) </span>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('or_013'); ?>" alt="<?php echo $l->m('or_013'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="ro_type"> <span class="pad20"> <?php echo $l->m('or_012'); ?> </span> </label>
                    <input name="ro_type" type="radio" value="1" class="chkBox" />
                    <select class="fldm" id="ro_auto" name="ro_auto">
                      <optgroup label="<?php echo $l->m('or_020'); ?>">
                      <option value="P" selected="selected"><?php echo $l->m('or_014'); ?></option>
                      <option value="p" ><?php echo $l->m('or_015'); ?></option>
                      </optgroup>
                      <optgroup label="<?php echo $l->m('or_021'); ?>">
                      <option value="l" ><?php echo $l->m('or_016'); ?></option>
                      <option value="L"><?php echo $l->m('or_017'); ?></option>
                      </optgroup>
                      <optgroup label="<?php echo $l->m('or_022'); ?>">
                      <option value="x"><?php echo $l->m('or_018'); ?></option>
                      </optgroup>
                    </select>
                    <span class="frmText"> (<?php echo $l->m('or_099'); ?>: 90&deg;) </span>
                  </div>
                  <div id="roclDiv" class="showit">
                    <div class="rowDiv">
                      <label for="or0_col"> <span class="pad20"> <?php echo $l->m('or_019'); ?> </span> </label>
                      <input class="fldsm" onChange="selColor(this);" type="text" id="or0_col" name="or0_col" size="7" value="#ffffff" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                      <input onClick="selColor(this);" class="fldCol" type="button" name="or0_icol" id="or0_icol" value="" style="background-color: #ffffff;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                      <span class="frmText"> (<?php echo $l->m('to_098'); ?>) </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!- // orDiv -->
            <!- filters ---------------------------------------------------------- -->
            <div id="fiDiv" class="hideit">
              <div class="floatWrap">
                <div class="rowDiv">
                  <div class="btnRight">
                    <img src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" alt="<?php echo $l->m('im_098'); ?>" title="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'efDiv','showit','toDiv','showit');" /><img src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" alt="<?php echo $l->m('im_099'); ?>" title="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'efDiv','hideit','toDiv','hideit');" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('co_003'); ?>" alt="<?php echo $l->m('co_003'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="chkColorize"> <span class="title"> <?php echo $l->m('co_002'); ?> </span> </label>
                  <input name="chkColorize" id="chkColorize" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkColorize','chkColorize');" />
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" alt="<?php echo $l->m('im_098'); ?>" title="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'efDiv','showit');" /><img src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" alt="<?php echo $l->m('im_099'); ?>" title="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'efDiv','hideit');" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('co_005'); ?>" alt="<?php echo $l->m('co_005'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="chkEffects"> <span class="pad10"> <span class="title"> <?php echo $l->m('co_004'); ?> </span> </span> </label>
                  <input name="chkEffects" id="chkEffects" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkColorize','chkEffects','chkTouchup');" />
                </div>
                <div class="clrFloat">
                </div>
                <div id="efDiv" class="hideit">
                  <div class="rowDiv">
                    <label for="co_type"> <span class="pad20"> <?php echo $l->m('ef_003'); ?> </span> </label>
                    <input name="co_type" type="radio" value="0" class="chkBox" onClick="changeClass(1,'efcoDiv','hideit');" checked="checked" />
                    <span class="frmText"> (<?php echo $l->m('ef_099'); ?>) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="co_type"> <span class="pad20"> <?php echo $l->m('ef_004'); ?> </span> </label>
                    <input name="co_type" type="radio" value="1" class="chkBox" onClick="changeClass(1,'efcoDiv','hideit');"/>
                  </div>
                  <div class="rowDiv">
                    <label for="co_type"> <span class="pad20"> <?php echo $l->m('ef_005'); ?> </span> </label>
                    <input name="co_type" type="radio" value="2" class="chkBox" onClick="changeClass(1,'efcoDiv','hideit');" />
                    <select class="fldm" id="tb_thres" name="tb_thres">
                      <?php echo writeOptions(1,255,1,1,'',128); ?>
                    </select>
                    <span class="frmText"> (<?php echo $l->m('ef_099'); ?>: 128) </span>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img onClick="changeClass(1,'efseDiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'efseDiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('ef_007'); ?>" alt="<?php echo $l->m('ef_007'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="co_type"> <span class="pad20"> <?php echo $l->m('ef_006'); ?> </span> </label>
                    <input name="co_type" type="radio" value="3" class="chkBox" onClick="changeClass(1,'efseDiv','showit');" />
                    <input class="fldsm" onChange="selColor(this);" type="text" id="se0_col"  name="se0_col"size="7" value="#a28065" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                    <input onClick="selColor(this);" class="fldCol" type="button" name="se0_icol" id="se0_icol" value="" style="background-color: #a28065;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                    <span class="frmText"> (<?php echo $l->m('ef_099'); ?>: #a28065) </span>
                  </div>
                  <div class="clrFloat">
                  </div>
                  <div id="efseDiv" class="hideit">
                    <div class="rowDiv">
                      <label for="se_in"> <span class="pad30"> <?php echo $l->m('ef_008'); ?> </span> </label>
                      <span class="pad20">
                      <select class="fldm" id="se_in" name="se_in">
                        <?php echo writeOptions(1,100,1,1,'',50); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('ef_099'); ?>: 50) </span> </span>
                    </div>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img onClick="changeClass(1,'efcoDiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'efcoDiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('ef_010'); ?>" alt="<?php echo $l->m('ef_010'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="co_type"> <span class="pad20"> <?php echo $l->m('ef_009'); ?> </span> </label>
                    <input name="co_type" type="radio" value="4" class="chkBox" onClick="changeClass(1,'efcoDiv','showit');" />
                    <input class="fldsm" onChange="selColor(this);" type="text" id="co0_col"  name="co0_col"size="7" value="#ffffff" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                    <input onClick="selColor(this);" class="fldCol" type="button" name="co0_icol" id="co0_icol" value="" style="background-color: #ffffff;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                    <span class="frmText"> (<?php echo $l->m('ef_099'); ?>: #ffffff) </span>
                  </div>
				  <div class="clrFloat">
                  </div>
                  <div id="efcoDiv" class="hideit">
                    <div class="rowDiv">
                      <label for="co_in"> <span class="pad30"> <?php echo $l->m('ef_011'); ?> </span> </label>
                      <span class="pad20">
                      <select class="fldm" id="co_in" name="co_in">
                        <?php echo writeOptions(1,100,1,1,'',25); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('ef_099'); ?>: 25) </span> </span>
                    </div>
                  </div>
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" alt="<?php echo $l->m('im_098'); ?>" title="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'toDiv','showit');" /><img src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" alt="<?php echo $l->m('im_099'); ?>" title="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'toDiv','hideit');" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('co_007'); ?>" alt="<?php echo $l->m('co_007'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="chkTouchup"> <span class="pad10"> <span class="title"> <?php echo $l->m('co_006'); ?> </span> </span> </label>
                  <input name="chkTouchup" id="chkTouchup" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkColorize','chkEffects','chkTouchup');" />
                </div>
                <div class="clrFloat">
                </div>
                <div id="toDiv" class="hideit">
                  <div class="rowDiv">
                    <label for="chkGamma"> <span class="pad20"> <?php echo $l->m('to_003'); ?> </span> </label>
                    <input name="chkGamma" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB'); setchkBox('chkColorize','chkTouchup','chkEffects');" />
                    <select class="fldm" id="co_ga" name="co_ga" >
                      <?php echo writeOptions(-50,50,1,10,'',10); ?>
                    </select>
                    <span class="frmText"> (<?php echo $l->m('to_099'); ?>: 1) </span>
                  </div>
                  <div class="rowDiv">
                    <label for="chkSaturation"> <span class="pad20"> <?php echo $l->m('to_004'); ?> </span> </label>
                    <input name="chkSaturation" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB'); setchkBox('chkColorize','chkTouchup','chkEffects');" />
                    <select class="fldm" id="co_ds" name="co_ds" >
                      <?php echo writeOptions(-100,100,1,1,'',0); ?>
                    </select>
                    <span class="frmText"> (<?php echo $l->m('to_099'); ?>: 0) </span>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('to_029'); ?>" alt="<?php echo $l->m('to_029'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="chkBlur"> <span class="pad20"> <?php echo $l->m('to_028'); ?> </span> </label>
                    <input name="chkBlur" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB'); setchkBox('chkColorize','chkTouchup','chkEffects');" />
                    <select class="fldm" id="co_bl" name="co_bl" >
                      <?php echo writeOptions(1,25,1,1,'',1); ?>
                    </select>
                    <span class="frmText"> (<?php echo $l->m('to_099'); ?>: 1) </span>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" alt="<?php echo $l->m('im_098'); ?>" title="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'toumDiv','showit');" /><img src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" alt="<?php echo $l->m('im_099'); ?>" title="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'efDiv','hideit','toumDiv','hideit');" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('to_006'); ?>" alt="<?php echo $l->m('to_006'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="chkUnsharp"> <span class="pad20"> <?php echo $l->m('to_005'); ?> </span> </label>
                    <input id="chkUnsharp" name="chkUnsharp" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB'); setchkBox('chkColorize','chkTouchup','chkEffects');" />
                    <span class="frmText"> <?php echo $l->m('to_007'); ?> </span>
                  </div>
                  <div class="clrFloat">
                  </div>
                  <div id="toumDiv" class="hideit">
                    <div class="rowDiv">
                      <label for="wz_usr"> <span class="pad30"> <?php echo $l->m('to_008'); ?> </span> </label>
                      <div class="btnRight">
                        <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('to_009'); ?>" alt="<?php echo $l->m('to_009'); ?>" width="16" height="16" border="0" />
                      </div>
                      <span class="pad20">
                      <select class="fldm" id="wz_usr" name="wz_usr">
                        <?php echo writeOptions(3,40,1,10,'',10); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('to_099'); ?>: 1) </span> </span>
                    </div>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('to_011'); ?>" alt="<?php echo $l->m('to_011'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="wz_usa"> <span class="pad30"> <?php echo $l->m('to_010'); ?> </span> </label>
                      <span class="pad20">
                      <select class="fldm" id="wz_usa" name="wz_usa">
                        <?php echo writeOptions(50,500,1,1,'&#37;',100); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('to_099'); ?>: 100%) </span> </span>
                    </div>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('to_013'); ?>" alt="<?php echo $l->m('to_013'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="wz_ust"> <span class="pad30"> <?php echo $l->m('to_012'); ?> </span> </label>
                      <span class="pad20">
                      <select class="fldm" id="wz_ust" name="wz_ust">
                        <?php echo writeOptions(0,255,1,1,'',3); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('to_099'); ?>: 3) </span> </span>
                    </div>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" alt="<?php echo $l->m('im_098'); ?>" title="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'toelDiv','showit');" /><img src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" alt="<?php echo $l->m('im_099'); ?>" title="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'efDiv','hideit','toelDiv','hideit');" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('to_016'); ?>" alt="<?php echo $l->m('to_016'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="chkLevel"> <span class="pad20"> <?php echo $l->m('to_014'); ?> </span> </label>
                    <input id="chkLevel" name="chkUnsharp" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB'); setchkBox('chkColorize','chkTouchup','chkEffects');" />
                    <span class="frmText"> <?php echo $l->m('to_015'); ?> </span>
                  </div>
				  <div class="clrFloat">
                  </div>
                  <div id="toelDiv" class="hideit">
                    <div class="rowDiv">
                      <label for="wz_cha"> <span class="pad30"> <?php echo $l->m('to_017'); ?> </span> </label>
                      <span class="pad20">
                      <select class="fldm" id="wz_cha" name="wz_cha">
                        <option value="*" selected="selected"><?php echo $l->m('to_019'); ?></option>
                        <option value="r"><?php echo $l->m('to_020'); ?></option>
                        <option value="g"><?php echo $l->m('to_021'); ?></option>
                        <option value="b"><?php echo $l->m('to_022'); ?></option>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('to_099'); ?>: <?php echo $l->m('to_019'); ?>) </span> </span>
                    </div>
                    <div class="rowDiv">
                      <label for="wz_min"> <span class="pad30"> <?php echo $l->m('to_023'); ?> </span> </label>
                      <span class="pad20">
                      <select class="fldm" id="wz_min" name="wz_min">
                        <option value="-1" selected="selected"><?php echo $l->m('to_027'); ?></option>
                        <?php echo writeOptions(0,255,1,1,'','999'); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('to_099'); ?>: <?php echo $l->m('to_027'); ?>) </span> </span>
                    </div>
                    <div class="rowDiv">
                      <label for="wz_max"> <span class="pad30"> <?php echo $l->m('to_025'); ?> </span> </label>
                      <span class="pad20">
                      <select class="fldm" id="wz_max" name="wz_max">
                        <option value="-1" selected="selected"><?php echo $l->m('to_027'); ?></option>
                        <?php echo writeOptions(0,255,1,1,'','999'); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('to_099'); ?>: <?php echo $l->m('to_027'); ?>) </span> </span>
                    </div>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" alt="<?php echo $l->m('im_098'); ?>" title="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'whitebDiv','showit');" /><img src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" alt="<?php echo $l->m('im_099'); ?>" title="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'whitebDiv','hideit');" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('to_032'); ?>" alt="<?php echo $l->m('to_032'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="chkWhiteB"> <span class="pad20"> <?php echo $l->m('to_030'); ?> </span> </label>
                    <input id="chkWhiteB" name="chkWhiteB" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkTouchup','chkGamma','chkSaturation','chkBlur','chkUnsharp','chkLevel','chkWhiteB'); setchkBox('chkColorize','chkTouchup','chkEffects');" />
                    <span class="frmText"> <?php echo $l->m('to_031'); ?> </span>
                  </div>
                  <div class="clrFloat">
                  </div>
                  <div id="whitebDiv" class="hideit">
                    <div class="rowDiv">
                      <label for="wb0_col"> <span class="pad30"> <?php echo $l->m('ef_009'); ?> </span> </label>
                      <input class="fldsm" onChange="selColor(this);" type="text" id="wb0_col"  name="wb0_col"size="7" value="#ffffff" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                      <input onClick="selColor(this);" class="fldCol" type="button" name="wb0_icol" id="wb0_icol" value="" style="background-color: #ffffff;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                      <span class="frmText"> (<?php echo $l->m('ef_099'); ?>: #ffffff) </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!- // fiDiv -->
            <!- watermark -------------------------------------------------------- -->
            <div id="wmDiv" class="hideit">
              <div class="floatWrap">
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="changeClass(1,'tb_wm','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'tb_wm','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('wm_003'); ?>" alt="<?php echo $l->m('wm_003'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="or_chkOr"> <span class="title"> <?php echo $l->m('wm_002'); ?> </span> </label>
                  <input name="chkWatermark" id="chkWatermark" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkWatermark','chkWatermark');" />
                </div>
                <div class="clrFloat">
                </div>
                <div id="tb_wm" class="showit">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('wm_007'); ?>" alt="<?php echo $l->m('wm_007'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="wm_type"> <span class="pad10"> <?php echo $l->m('wm_004'); ?> </span> </label>
                    <input name="wm_type" type="radio" value="0" class="chkBox" onClick="changeClass(1,'tb_wmt','showit','wmPrevDiv','hideit','tb_wmset','showit');" checked="checked" />
                    <span class="frmText"> <?php echo $l->m('wm_005'); ?> </span>
                    <input name="wm_type" type="radio" value="1" class="chkBox" onClick="changeClass(1,'tb_wmt','hideit','wmPrevDiv','showit','tb_wmset','showit');" />
                    <span class="frmText"> <?php echo $l->m('wm_006'); ?> </span>
                  </div>
                  <div id="tb_wmt" class="showit">
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="selSymbol('wm_text');" src="images/symbols_off.gif" onMouseOver="this.src='images/symbols.gif';" onMouseOut="this.src='images/symbols_off.gif';" title="<?php echo $l->m('wm_010'); ?>" alt="<?php echo $l->m('wm_010'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('wm_009'); ?>" alt="<?php echo $l->m('wm_009'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="wm_text"> <span class="pad10"> <?php echo $l->m('wm_008'); ?> </span> </label>
                      <textarea name="wm_text" cols="" rows="1" wrap="PHYSICAL" class="fldmlg" id="wm_text"></textarea>
                    </div>
                    <div class="rowDiv">
                      <label for="wm0_col"> <span class="pad10"> <?php echo $l->m('wm_011'); ?> </span> </label>
                      <input class="fldsm" onChange="selColor(this);" type="text" name="wm0_col" id="wm0_col" size="7" value="#000000" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                      <input onClick="selColor(this);" class="fldCol" type="button" name="wm0_icol" id="wm0_icol" value="" style="background-color: #000000;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                      <span class="frmText"> (<?php echo $l->m('wm_099'); ?>: #000000) </span>
                    </div>
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('wm_015'); ?>" alt="<?php echo $l->m('wm_015'); ?>" width="16" height="16" border="0" />
                      </div>
                      <label for="wmf_type"> <span class="pad10"> <?php echo $l->m('wm_012'); ?> </span> </label>
                      <input name="wmf_type" type="radio" value="0" class="chkBox" onClick="changeClass(1,'tb_wmts','showit','tb_wmtt','hideit');" checked="checked" />
                      <span class="frmText"> <?php echo $l->m('wm_013'); ?> </span>
                      <input name="wmf_type" type="radio" value="1" class="chkBox" onClick="changeClass(1,'tb_wmts','hideit','tb_wmtt','showit');" />
                      <span class="frmText"> <?php echo $l->m('wm_014'); ?> </span>
                    </div>
                    <div class="clrFloat">
                    </div>
                    <div id="tb_wmtt" class="hideit">
                      <div class="rowDiv">
                        <label for="wm_font"> <span class="pad10"> <?php echo $l->m('wm_032'); ?> </span> </label>
                        <select class="fldm" id="wm_font" name="wm_font">
                          <?php echo getItems($cfg['fonts'], 'ft', 'ttf'); ?>
                        </select>
                        <span class="frmText"> (<?php echo $l->m('wm_099'); ?>: arial) </span>
                      </div>
                      <div class="rowDiv">
                        <label for="wm_size"> <span class="pad10"> <?php echo $l->m('wm_017'); ?> </span> </label>
                        <select class="fldm" id="wm_size" name="wm_size">
                          <?php echo writeOptions(6,30,1,1,'px',10); ?>
                        </select>
                        <span class="frmText"> (<?php echo $l->m('wm_099'); ?>: 10px) </span>
                      </div>
                      <div class="rowDiv">
                        <label for="wm_angle"> <span class="pad10"> <?php echo $l->m('wm_018'); ?> </span> </label>
                        <select class="fldm" id="wm_angle" name="wm_angle">
                          <?php echo writeOptions(0,90,1,1,'&deg',0); ?>
                        </select>
                        <span class="frmText"> (<?php echo $l->m('wm_099'); ?>: 0&deg;) </span>
                      </div>
                    </div>
                    <div id="tb_wmts" class="showit">
                      <div class="rowDiv">
                        <label for="wms_size"> <span class="pad10"> <?php echo $l->m('wm_017'); ?> </span> </label>
                        <select class="fldm" id="wms_size" name="wms_size">
                          <?php echo writeOptions(1,5,1,1,'',3); ?>
                        </select>
                        <span class="frmText"> (<?php echo $l->m('wm_099'); ?>: 3) </span>
                      </div>
                    </div>
                  </div>
                  <div id="wmPrevDiv" class="hideit">
                    <div class="mtop10">
                      <div class="rowDiv">
                        <div class="imgtbl">
                          <?php echo getItems($cfg['mark'], 'wm', $cfg['valid']); ?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div id="tb_wmset" class="showit">
                    <div class="rowDiv">
                      <label for="wm_opacity"> <span class="pad10"> <?php echo $l->m('wm_019'); ?> </span> </label>
                      <select class="fldm" id="wm_opacity" name="wm_opacity">
                        <?php echo writeOptions(5,100,5,1,'&#37',75); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wm_099'); ?>: 75%) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="wm_space"> <span class="pad10"> <?php echo $l->m('wm_020'); ?> </span> </label>
                      <select class="fldm" id="wm_space" name="wm_space">
                        <?php echo writeOptions(0,20,1,1,'&#37;',5); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wm_099'); ?>: 5%) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="wm_align"> <span class="pad10"> <?php echo $l->m('wm_021'); ?> </span> </label>
                      <select class="fldm" id="wm_align" name="wm_align">
                        <option value="TL"><?php echo $l->m('wm_022'); ?></option>
                        <option value="T"><?php echo $l->m('wm_023'); ?></option>
                        <option value="TR"><?php echo $l->m('wm_024'); ?></option>
                        <option value="L"><?php echo $l->m('wm_025'); ?></option>
                        <option value="R"><?php echo $l->m('wm_026'); ?></option>
                        <option value="BL"><?php echo $l->m('wm_027'); ?></option>
                        <option value="B"><?php echo $l->m('wm_028'); ?></option>
                        <option value="BR" selected="selected"><?php echo $l->m('wm_029'); ?></option>
                        <option value="*"><?php echo $l->m('wm_030'); ?></option>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wm_099'); ?>: <?php echo $l->m('wm_029'); ?>) </span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!- // wmDiv -->
            <!- overlay ---------------------------------------------------------- -->
            <div id="ovDiv" class="showit">
              <div class="floatWrap">
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="changeClass(1,'ovLibDiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'ovLibDiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('ov_003'); ?>" alt="<?php echo $l->m('ov_003'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="chkOverlay"> <span class="title"> <?php echo $l->m('ov_002'); ?> </span> </label>
                  <input name="chkOverlay"  id="chkOverlay" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkOverlay','chkOverlay');" />
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('ov_007'); ?>" alt="<?php echo $l->m('ov_007'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="ov_type"> <span class="pad10"> <?php echo $l->m('ov_004'); ?> </span> </label>
                  <input name="ov_type" type="radio" value="0" class="chkBox" checked="checked" />
                  <span class="frmText"> <?php echo $l->m('ov_005'); ?> </span>
                  <input name="ov_type" type="radio" value="1" class="chkBox" />
                  <span class="frmText"> <?php echo $l->m('ov_006'); ?> </span>
                </div>
                <div class="rowDiv">
                  <label for="ov_opacity"> <span class="pad10"> <?php echo $l->m('ov_008'); ?> </span> </label>
                  <select class="fldm" id="ov_opacity" name="ov_opacity">
                    <?php echo writeOptions(0,100,5,1,'&#37',75); ?>
                  </select>
                  <span class="frmText"> (<?php echo $l->m('ov_099'); ?>: 75%) </span>
                </div>
                <div class="rowDiv">
                  <label for="ov_space"> <span class="pad10"> <?php echo $l->m('ov_009'); ?> </span> </label>
                  <select class="fldm" id="ov_space" name="ov_space">
                    <?php echo writeOptions(0,49,1,100,'&#37;',10); ?>
                  </select>
                  <span class="frmText"> (<?php echo $l->m('ov_099'); ?>: 0.10%) </span>
                </div>
                <div id="ovLibDiv" class="hideit">
                  <input id="ovFile" name="ovFile" type="hidden" value="" />
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('in_003'); ?>" alt="<?php echo $l->m('in_003'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="ov_ilibs"> <span class="pad10"> <span class="title"> <?php echo $l->m('in_002'); ?> </span> </span> </label>
                    <select class="fldlg" id="ov_ilibs" name="ov_ilibs" size="1" onChange="ov_ilibsClick(this.value, '');" >
                      <?php echo liboptions($cfg['ilibs'], '', absPath($cfg['olay']), 'ov'); ?>
                    </select>
                  </div>
                  <div class="mtop5">
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="fullSizeView('ov'); return false;" src="images/prev_off.gif" onMouseOver="this.src='images/prev.gif';" onMouseOut="this.src='images/prev_off.gif';" alt="<?php echo $l->m('in_007'); ?>" title="<?php echo $l->m('in_007'); ?>" width="16" height="16" border="0" />
                      </div>
                      <div class="ovPrevDiv">
                        <iframe name="ovPrevFrame" id="ovPrevFrame" src="images/noImg.gif" style="width: 100%; height: 100%;" scrolling="no" marginheight="0" marginwidth="0" frameborder="0"></iframe>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!- // ovDiv -->
            <!- mask ------------------------------------------------------------- -->
            <div id="msDiv" class="hideit">
              <div class="floatWrap">
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="changeClass(1,'msPrevDiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'msPrevDiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('ms_003'); ?>" alt="<?php echo $l->m('ms_003'); ?>" width="16" height="16" border="0" />
                  </div>
                  <label for="chkMask"> <span class="title"> <?php echo $l->m('ms_002'); ?> </span> </label>
                  <input name="chkMask"  id="chkMask" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkMask','chkMask');" />
                </div>
                <div class="clrFloat">
                </div>
                <div id="mscldiv" class="showit">
                  <div class="rowDiv">
                    <label for="ms0_col"> <span class="pad10"> <?php echo $l->m('ms_004'); ?> </span> </label>
                    <input class="fldsm" onChange="selColor(this);" type="text" name="ms0_col" id="ms0_col" size="7" value="#ffffff" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                    <input onClick="selColor(this);" class="fldCol" type="button" name="ms0_icol" id="ms0_icol" value="" style="background-color: #ffffff;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                    <span class="frmText"> (<?php echo $l->m('to_098'); ?>: #ffffff) </span>
                  </div>
                </div>
                <div id="msPrevDiv" class="showit">
                  <div class="mtop10">
                    <div class="rowDiv">
                      <div class="btnRight">
                        <img onClick="fullSizeView('ms'); return false;" src="images/prev_off.gif" onMouseOver="this.src='images/prev.gif';" onMouseOut="this.src='images/prev_off.gif';" alt="<?php echo $l->m('in_007'); ?>" title="<?php echo $l->m('in_007'); ?>" width="16" height="16" border="0" />
                      </div>
                      <div class="imgtbl">
                        <?php echo getItems($cfg['mask'], 'ms', $cfg['valid']); ?>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <!- // msDiv -->
            <!- wizard ----------------------------------------------------------- -->
            <div id="wzDiv" class="hideit">
              <div class="floatWrap">
                <!- wizard menu bar -------------------------------------------------- -->
                <div id="wzMenuBarDiv">
                  <ul>
                    <li class="btnUp" id="btn_wzsl"><img src="images/wzsl.gif" alt="<?php echo $l->m('wz_004'); ?>" title="<?php echo $l->m('wz_002'); ?>" width="16" height="16" /></li>
                    <li class="btnUp" id="btn_wzbe"><img src="images/wzbe.gif" alt="<?php echo $l->m('wz_004'); ?>" title="<?php echo $l->m('wz_004'); ?>" width="16" height="16" /></li>
                    <li class="btnUp" id="btn_wzfr"><img src="images/wzfr.gif" alt="<?php echo $l->m('wz_006'); ?>" title="<?php echo $l->m('wz_006'); ?>" width="16" height="16" /></li>
                    <li class="btnUp" id="btn_wzsh"><img src="images/wzsh.gif" alt="<?php echo $l->m('wz_008'); ?>" title="<?php echo $l->m('wz_008'); ?>" width="16" height="16" /></li>
                    <li class="btnUp" id="btn_wzbr"><img src="images/wzbr.gif" alt="<?php echo $l->m('wz_010'); ?>" title="<?php echo $l->m('wz_010'); ?>" width="16" height="16" /></li>
                    <li class="btnUp" id="btn_wzrc"><img src="images/wzco.gif" alt="<?php echo $l->m('wz_012'); ?>" title="<?php echo $l->m('wz_012'); ?>" width="16" height="16" /></li>
                    <li class="btnUp" id="btn_wzel"><img src="images/wzel.gif" alt="<?php echo $l->m('wz_014'); ?>" title="<?php echo $l->m('wz_014'); ?>" width="16" height="16" /></li>
                  </ul>
                </div>
                <div class="clrFloat">
                </div>
                <!- // wzMenuBarDiv -->
                <div id="wzslDiv">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" alt="open all sections" title="open all sections" width="16" height="16" border="0" onClick="changeClass(1,'beDiv','showit','frDiv','showit','shDiv','showit','brDiv','showit','rcDiv','showit','elDiv','showit');" /><img src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" alt="close all sections" title="close all sections" width="16" height="16" border="0" onClick="changeClass(1,'beDiv','hideit','frDiv','hideit','shDiv','hideit','brDiv','hideit','rcDiv','hideit','elDiv','hideit')" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('wz_003'); ?>" alt="<?php echo $l->m('wz_003'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="chkWizard"> <span class="title"> <?php echo $l->m('wz_002'); ?> </span> </label>
                    <input id="chkWizard" name="chkWizard" type="checkbox" class="chkBox" value="" onClick="setchkBox('chkWizard','chkWizard');" />
                  </div>
                </div>
                <!- bevel ------------------------------------------------------------ -->
                <div id="wzbeDiv">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img onClick="changeClass(1,'beDiv','showit')" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'beDiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('wz_005'); ?>" alt="<?php echo $l->m('wz_005'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="chkBevel"> <span class="pad10"> <span class="title"> <?php echo $l->m('wz_004'); ?> </span> </span> </label>
                    <input name="chkBevel" id="chkBevel" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkWizard','chkBevel','chkFrame','chkShadow','chkBorder','chkEllipse');" />
                  </div>
                  <div class="clrFloat">
                  </div>
                  <div id="beDiv" class="hideit">
                    <div class="rowDiv">
                      <label for="wz_BevelWidth"> <span class="pad20"> <?php echo $l->m('be_003'); ?> </span> </label>
                      <select class="fldm" id="wz_BevelWidth" name="wz_BevelWidth">
                        <?php echo writeOptions(1,20,1,1,'px',10)?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wz_099'); ?>: 10px) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="be0_icol"> <span class="pad20"> <?php echo $l->m('be_004'); ?> </span> </label>
                      <input class="fldsm" onChange="selColor(this);" type="text" name="be0_col" id="be0_co0" size="7" value="#ffffff" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                      <input onClick="selColor(this);" class="fldCol" type="button" name="be0_icol" id="be0_icol" value="" style="background-color: #ffffff;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                      <span class="frmText"> (<?php echo $l->m('be_005'); ?>) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="be1_col"> <span class="pad20"> <?php echo $l->m('be_006'); ?> </span> </label>
                      <input class="fldsm" onChange="selColor(this);" type="text" name="be1_col" id="be1_col" size="7" value="#000000" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                      <input onClick="selColor(this);" class="fldCol" type="button" name="be1_icol" id="be1_icol" value="" style="background-color: #000000;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                      <span class="frmText"> (<?php echo $l->m('be_007'); ?>) </span>
                    </div>
                  </div>
                </div>
                <!- // wzbeDiv -->
                <!- frame ------------------------------------------------------------ -->
                <div id="wzfrDiv">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img onClick="changeClass(1,'frDiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'frDiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('wz_007'); ?>" alt="<?php echo $l->m('wz_007'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="chkFrame"> <span class="pad10"> <span class="title"> <?php echo $l->m('wz_006'); ?> </span> </span> </label>
                    <input name="chkFrame" id="chkFrame" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkWizard','chkBevel','chkFrame','chkShadow','chkBorder','chkEllipse');" />
                  </div>
                  <div class="clrFloat">
                  </div>
                  <div id="frDiv" class="hideit">
                    <div class="rowDiv">
                      <label for="fr_w"> <span class="pad20"> <?php echo $l->m('fr_003'); ?> </span> </label>
                      <select class="fldm" id="fr_w" name="fr_w">
                        <?php echo writeOptions(1,25,1,1,'px',3); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('fr_004'); ?>) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="fr_wb"> <span class="pad20"> <?php echo $l->m('fr_005'); ?> </span> </label>
                      <select class="fldm" id="fr_wb" name="fr_wb">
                        <?php echo writeOptions(1,25,1,1,'px',1); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('fr_006'); ?>) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="fr0_col"> <span class="pad20"> <?php echo $l->m('fr_007'); ?> </span> </label>
                      <input class="fldsm" onChange="selColor(this);" type="text" name="fr0_col" id="fr0_col" size="7" value="#6f6f6f" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                      <input onClick="selColor(this);" class="fldCol" type="button" name="fr0_icol" id="fr0_icol" value="" style="background-color: #6f6f6f;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                      <span class="frmText"> (<?php echo $l->m('fr_008'); ?>) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="fr1_col"> <span class="pad20"> <?php echo $l->m('fr_009'); ?> </span> </label>
                      <input class="fldsm" onChange="selColor(this);" type="text" name="fr1_col" id="fr1_col" size="7" value="#ffffff" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                      <input onClick="selColor(this);" class="fldCol" type="button" name="fr1_icol" id="fr1_icol" value="" style="background-color: #ffffff;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                      <span class="frmText"> (<?php echo $l->m('fr_010'); ?>) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="fr2_col"> <span class="pad20"> <?php echo $l->m('fr_011'); ?> </span> </label>
                      <input class="fldsm" onChange="selColor(this);" type="text" name="fr2_col" id="fr2_col" size="7" value="#000000" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                      <input onClick="selColor(this);" class="fldCol" type="button" name="fr2_icol" id="fr2_icol" value="" style="background-color: #000000;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                      <span class="frmText"> (<?php echo $l->m('fr_012'); ?>) </span>
                    </div>
                  </div>
                </div>
                <!- // wzfrDiv -->
                <!- shadow ----------------------------------------------------------- -->
                <div id="wzshDiv">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img onClick="changeClass(1,'shDiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'shDiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('wz_009'); ?>" alt="<?php echo $l->m('wz_009'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="chkShadow"> <span class="pad10"> <span class="title"> <?php echo $l->m('wz_008'); ?> </span> </span> </label>
                    <input name="chkShadow" id="chkShadow" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkWizard','chkBevel','chkFrame','chkShadow','chkBorder','chkEllipse');" />
                  </div>
                  <div class="clrFloat">
                  </div>
                  <div id="shDiv" class="hideit">
                    <div class="hideit">
                      <!- not yet implemented -->
                      <label for="wz_ShadowWidth"> <span class="pad20"> <?php echo $l->m('sh_003'); ?> </span> </label>
                      <select class="fldm" id="wz_ShadowWidth" name="wz_ShadowWidth">
                        <?php echo writeOptions(1,20,1,1,'px',10)?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wz_099'); ?>: 10px) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="sh_margin"> <span class="pad20"> <?php echo $l->m('sh_004'); ?> </span> </label>
                      <select class="fldm" id="sh_margin" name="sh_margin">
                        <?php echo writeOptions(0,50,1,1,'px',5); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wz_099'); ?>: 5px) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="sh_angle"> <span class="pad20"> <?php echo $l->m('sh_005'); ?> </span> </label>
                      <select class="fldm" id="sh_angle" name="sh_angle">
                        <?php echo writeOptions(0,360,1,1,'&deg',225); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wz_099'); ?>: 225&deg;) </span>
                    </div>
                    <div class="hideit">
                      <!- not yet implemented -->
                      <label for="sh_fade"> <span class="pad20"> <?php echo $l->m('sh_006'); ?> </span> </label>
                      <select class="fldm" id="sh_fade" name="sh_fade">
                        <?php echo writeOptions(1,20,1,1,'',1); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wz_099'); ?>: 1) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="ds0_col"> <span class="pad20"> <?php echo $l->m('sh_007'); ?> </span> </label>
                      <input class="fldsm" onChange="selColor(this);" type="text" name="ds0_col" id="ds0_col" size="7" value="#000000" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                      <input onClick="selColor(this);" class="fldCol" type="button" name="ds0_icol" id="ds0_icol" value="" style="background-color: #000000;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                      <span class="frmText"> (<?php echo $l->m('sh_008'); ?>) </span>
                    </div>
                  </div>
                </div>
                <!- // wzshDiv -->
                <!- borders ---------------------------------------------------------- -->
                <div id="wzbrDiv">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img onClick="changeClass(1,'brDiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'brDiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('wz_011'); ?>" alt="<?php echo $l->m('wz_011'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="chkBorder"> <span class="pad10"> <span class="title"> <?php echo $l->m('wz_010'); ?> </span> </span> </label>
                    <input name="chkBorder" id="chkBorder" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkWizard','chkBevel','chkFrame','chkShadow','chkBorder','chkCorner','chkEllipse');" />
                  </div>
                  <div class="clrFloat">
                  </div>
                  <div id="brDiv" class="hideit">
                    <div class="rowDiv">
                      <label for="brwidth"> <span class="pad20"> <?php echo $l->m('br_003'); ?> </span> </label>
                      <select class="fldm" id="brwidth" name="brwidth">
                        <?php echo writeOptions(1,20,1,1,'px',1); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wz_099'); ?>: 1px) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="brxrad"> <span class="pad20"> <?php echo $l->m('br_004'); ?> </span> </label>
                      <select class="fldm" id="brxrad" name="brxrad">
                        <?php echo writeOptions(0,50,1,1,'px',20); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wz_099'); ?>: 20px) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="bryrad"> <span class="pad20"> <?php echo $l->m('br_005'); ?> </span> </label>
                      <select class="fldm" id="bryrad" name="bryrad">
                        <?php echo writeOptions(0,50,1,1,'px',20); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wz_099'); ?>: 20px) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="br1_col"> <span class="pad20"> <?php echo $l->m('br_006'); ?> </span> </label>
                      <input class="fldsm" onChange="selColor(this);" type="text" name="br1_col" id="br1_col" size="7" value="#6f6f6f" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                      <input onClick="selColor(this);" class="fldCol" type="button" name="br1_icol" id="br1_icol" value="" style="background-color: #6f6f6f;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                      <span class="frmText"> (<?php echo $l->m('br_007'); ?>) </span>
                    </div>
                    <div class="clrFloat">
                    </div>
                    <div id="recldiv" class="showit">
                      <div class="rowDiv">
                        <label for="br0_col"> <span class="pad20"> <?php echo $l->m('br_008'); ?> </span> </label>
                        <input class="fldsm" onChange="selColor(this);" type="text" name="br0_col" id="br0_col" size="7" value="#ffffff" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                        <input onClick="selColor(this);" class="fldCol" type="button" name="br0_icol" id="br0_icol" value="" style="background-color: #ffffff;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                        <span class="frmText"> (<?php echo $l->m('to_098'); ?>) </span>
                      </div>
                    </div>
                  </div>
                </div>
                <!- wzbrDiv -->
                <!- rounded corners -------------------------------------------------- -->
                <div id="wzrcDiv">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img onClick="changeClass(1,'rcDiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'rcDiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('wz_013'); ?>" alt="<?php echo $l->m('wz_013'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="chkCorner"> <span class="pad10"> <span class="title"> <?php echo $l->m('wz_012'); ?> </span> </span> </label>
                    <input name="chkCorner" id="chkCorner" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkWizard','chkBevel','chkFrame','chkShadow','chkBorder','chkCorner','chkEllipse');" />
                  </div>
                  <div class="clrFloat">
                  </div>
                  <div id="rcDiv" class="hideit">
                    <div class="rowDiv">
                      <label for="rcxrad"> <span class="pad20"> <?php echo $l->m('rc_003'); ?> </span> </label>
                      <select class="fldm" id="rcxrad" name="rcxrad">
                        <?php echo writeOptions(0,50,1,1,'px',20); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wz_099'); ?>: 20px) </span>
                    </div>
                    <div class="rowDiv">
                      <label for="rcyrad"> <span class="pad20"> <?php echo $l->m('rc_004'); ?> </span> </label>
                      <select class="fldm" id="rcyrad" name="rcyrad">
                        <?php echo writeOptions(0,50,1,1,'px',20); ?>
                      </select>
                      <span class="frmText"> (<?php echo $l->m('wz_099'); ?>: 20px) </span>
                    </div>
                    <div class="clrFloat">
                    </div>
                    <div id="rccldiv" class="showit">
                      <div class="rowDiv">
                        <label for="rc0_col"> <span class="pad20"> <?php echo $l->m('rc_005'); ?> </span> </label>
                        <input class="fldsm" onChange="selColor(this);" type="text" name="rc0_col" id="rc0_col" size="7" value="#ffffff" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                        <input onClick="selColor(this);" class="fldCol" type="button" name="rc0_icol" id="rc0_icol" value="" style="background-color: #ffffff;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                        <span class="frmText"> (<?php echo $l->m('to_098'); ?>) </span>
                      </div>
                    </div>
                  </div>
                </div>
                <!- wzrcDiv -->
                <!- ellipse ---------------------------------------------------------- -->
                <div id="wzelDiv">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="elclbtn0" onClick="changeClass(1,'elDiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" title="<?php echo $l->m('im_098'); ?>" alt="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img  id="elclbtn1" onClick="changeClass(1,'elDiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" title="<?php echo $l->m('im_099'); ?>" alt="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img class="hlpBtn" src="images/help_off.gif" onMouseOver="this.src='images/help.gif';" onMouseOut="this.src='images/help_off.gif';" onClick="alert(this.alt);" title="<?php echo $l->m('wz_015'); ?>" alt="<?php echo $l->m('wz_015'); ?>" width="16" height="16" border="0" />
                    </div>
                    <label for="chkEllipse"> <span class="pad10"> <span class="title"> <?php echo $l->m('wz_014'); ?> </span> </span> </label>
                    <input name="chkEllipse" id="chkEllipse" type="checkbox" value="" class="chkBox" onClick="setchkBox('chkWizard','chkBevel','chkFrame','chkShadow','chkBorder','chkEllipse');" />
                  </div>
                  <div class="clrFloat">
                  </div>
                  <div id="elDiv" class="hideit">
                    <div class="rowDiv">
                      <label for="el0_col"> <span class="pad20"> <?php echo $l->m('el_003'); ?> </span> </label>
                      <input class="fldsm" onChange="selColor(this);" type="text" name="el0_col" id="el0_col" size="7" value="#ffffff" maxlength="7" onKeyUp="RemoveInvalidChars(this, '[^#A-Fa-f0-9]'); return false;" />
                      <input onClick="selColor(this);" class="fldCol" type="button" name="el0_icol" id="el0_icol" value="" style="background-color: #ffffff;" alt="<?php echo $l->m('im_097'); ?>" title="<?php echo $l->m('im_097'); ?>" />
                      <span class="frmText"> (<?php echo $l->m('to_098'); ?>) </span>
                    </div>
                  </div>
                </div>
              </div>
              <!- // wzelDiv -->
            </div>
            <!- // wzDiv -->
            <!- toolbox settings ------------------------------------------------- -->
            <div id="seDiv" class="hideit">
              <div class="floatWrap">
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="changeClass(1,'irsdiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" alt="<?php echo $l->m('im_098'); ?>" title="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" alt="<?php echo $l->m('im_099'); ?>" title="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" onClick="changeClass(1,'irsdiv','hideit');" /><img id="i_chkResize_img" name="i_chkResize_img" onClick="changeClass(0,'btn_rs','btnDown'); btn_click('subMenuBarDiv','btn_rs');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"  width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                  </div>
                  <label for="i_chkResize"> <span class="title"> <?php echo $l->m('rs_001'); ?> </span> </label>
                  <input name="i_chkResize" type="checkbox" class="chkBox" id="i_chkResize" onClick="setchkBox('i_chkResize','i_chkResize')" value="" />
                </div>
                <div class="clrFloat">
                </div>
                <div id="irsdiv" class="hideit">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="i_rs_chkEnla_img" name="i_rs_chkEnla_img" onClick="changeClass(0,'btn_rs','btnDown'); btn_click('subMenuBarDiv','btn_rs');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                    </div>
                    <label for="i_rs_chkEnla"> <span class="pad10"> <?php echo $l->m('rs_020'); ?> </span> </label>
                    <input name="i_rs_chkEnla" id="i_rs_chkEnla" type="checkbox" class="chkBox" value=""onClick="setchkBox('i_chkResize','i_rs_chkEnla')" />
                  </div>
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img id="i_chkCrop_img" name="i_chkCrop_img" onClick="changeClass(0,'btn_cr','btnDown'); btn_click('subMenuBarDiv','btn_cr');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                  </div>
                  <label for="i_chkCrop"> <span class="title"> <?php echo $l->m('cr_001'); ?> </span> </label>
                  <input name="i_chkCrop" id="i_chkCrop" type="checkbox" value="" class="chkBox" onClick="setchkBox('i_chkCrop','i_chkCrop')" />
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="changeClass(1,'iordiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" alt="<?php echo $l->m('im_098'); ?>" title="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'iordiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" alt="<?php echo $l->m('im_099'); ?>" title="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(0,'btn_or','btnDown'); btn_click('subMenuBarDiv','btn_or');" id="i_chkOrientation_img" name="i_chkOrientation_img" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                  </div>
                  <label for="i_chkOrientation"> <span class="title"> <?php echo $l->m('or_001'); ?> </span> </label>
                  <input name="i_chkOrientation" id="i_chkOrientation" type="checkbox" value="" class="chkBox" onClick="setchkBox('i_chkOrientation','i_chkOrientation')" />
                </div>
                <div class="clrFloat">
                </div>
                <div id="iordiv" class="hideit">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="i_chkFlip_img" name="i_chkFlip_img" onClick="changeClass(0,'btn_or','btnDown'); btn_click('subMenuBarDiv','btn_or');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                    </div>
                    <label for="i_chkFlip"> <span class="pad10"> <?php echo $l->m('or_004'); ?> </span> </label>
                    <input name="i_chkFlip" id="i_chkFlip" type="checkbox" value="" class="chkBox" onClick="setchkBox('i_chkOrientation','i_chkFlip','i_chkRotate')" />
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="i_chkRotate_img" name="i_chkRotate_img" onClick="changeClass(0,'btn_or','btnDown'); btn_click('subMenuBarDiv','btn_or');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                    </div>
                    <label for="i_chkRotate"> <span class="pad10"> <?php echo $l->m('or_008'); ?> </span> </label>
                    <input name="i_chkRotate" id="i_chkRotate" type="checkbox" value="" class="chkBox" onClick="setchkBox('i_chkOrientation','i_chkFlip','i_chkRotate')" />
                  </div>
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="changeClass(1,'icodiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" alt="<?php echo $l->m('im_098'); ?>" title="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'icodiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" alt="<?php echo $l->m('im_099'); ?>" title="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(0,'btn_fi','btnDown'); btn_click('subMenuBarDiv','btn_fi');" id="i_chkColorize_img" name="i_chkColorize_img" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                  </div>
                  <label for="i_chkColorize"> <span class="title"> <?php echo $l->m('co_001'); ?> </span> </label>
                  <input name="i_chkColorize" id="i_chkColorize" type="checkbox" value="" class="chkBox" onClick="setchkBox('i_chkColorize','i_chkColorize')"/>
                </div>
                <div class="clrFloat">
                </div>
                <div id="icodiv" class="hideit">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="i_chkEffects_img" name="i_chkEffects_img" onClick="changeClass(0,'btn_fi','btnDown'); btn_click('subMenuBarDiv','btn_fi');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                    </div>
                    <label for="i_chkEffects"> <span class="pad10"> <?php echo $l->m('ef_001'); ?> </span> </label>
                    <input name="i_chkEffects" id="i_chkEffects" type="checkbox" class="chkBox" value="" onClick="setchkBox('i_chkColorize','i_chkTouchup','i_chkEffects');"/>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="i_chkTouchup_img" name="i_chkTouchup_img" onClick="changeClass(0,'btn_fi','btnDown'); btn_click('subMenuBarDiv','btn_fi');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                    </div>
                    <label for="i_chkTouchup"> <span class="pad10"> <?php echo $l->m('to_001'); ?> </span> </label>
                    <input name="i_chkTouchup" id="i_chkTouchup" type="checkbox" class="chkBox" value="" onClick="setchkBox('i_chkColorize','i_chkEffects','i_chkTouchup');"/>
                  </div>
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img id="i_chkWatermark_img" name="i_chkWatermark_img" onClick="changeClass(0,'btn_wm','btnDown'); btn_click('subMenuBarDiv','btn_wm');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                  </div>
                  <label for="i_chkWatermark"> <span class="title"> <?php echo $l->m('wm_001'); ?> </span> </label>
                  <input name="i_chkWatermark" id="i_chkWatermark" type="checkbox" value="" class="chkBox" onClick="setchkBox('i_chkWatermark','i_chkWatermark');"/>
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img id="i_chkOverlay_img" name="i_chkOverlay_img" onClick="changeClass(0,'btn_ov','btnDown'); btn_click('subMenuBarDiv','btn_ov');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                  </div>
                  <label for="i_chkOverlay"> <span class="title"> <?php echo $l->m('ov_001'); ?> </span> </label>
                  <input name="i_chkOverlay" id="i_chkOverlay" type="checkbox" value="" class="chkBox" onClick="setchkBox('i_chkOverlay','i_chkOverlay');"/>
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img id="i_chkMask_img" name="i_chkMask_img" onClick="changeClass(0,'btn_ms','btnDown'); btn_click('subMenuBarDiv','btn_ms');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                  </div>
                  <label for="i_chkMask"> <span class="title"> <?php echo $l->m('ms_001'); ?> </span> </label>
                  <input name="i_chkMask" id="i_chkMask" type="checkbox" value="" class="chkBox" onClick="setchkBox('i_chkMask','i_chkMask');"/>
                </div>
                <div class="rowDiv">
                  <div class="btnRight">
                    <img onClick="changeClass(1,'iwzdiv','showit');" src="images/osec_off.gif" onMouseOver="this.src='images/osec.gif';" onMouseOut="this.src='images/osec_off.gif';" alt="<?php echo $l->m('im_098'); ?>" title="<?php echo $l->m('im_098'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(1,'iwzdiv','hideit');" src="images/csec_off.gif" onMouseOver="this.src='images/csec.gif';" onMouseOut="this.src='images/csec_off.gif';" alt="<?php echo $l->m('im_099'); ?>" title="<?php echo $l->m('im_099'); ?>" width="16" height="16" border="0" /><img onClick="changeClass(0,'btn_wz','btnDown'); btn_click('subMenuBarDiv','btn_wz');" id="i_chkWizard_img" name="i_chkWizard_img" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                  </div>
                  <label for="i_chkWizard"> <span class="title"> <?php echo $l->m('wz_001'); ?> </span> </label>
                  <input name="i_chkWizard" id="i_chkWizard" type="checkbox" value="" class="chkBox" onClick="setchkBox('i_chkWizard','i_chkWizard')" />
                </div>
                <div class="clrFloat">
                </div>
                <div  id="iwzdiv" class="hideit">
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="i_chkBevel_img" name="i_chkBevel_img" onClick="changeClass(0,'btn_wz','btnDown'); btn_click('subMenuBarDiv','btn_wz');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                    </div>
                    <label for="i_chkBevel"> <span class="pad10"> <?php echo $l->m('be_001'); ?> </span> </label>
                    <input name="i_chkBevel" id="i_chkBevel" type="checkbox" class="chkBox" value="" onClick="setchkBox('i_chkWizard','i_chkBevel','i_chkFrame','i_chkShadow','i_chkBorder','i_chkCorner','i_chkEllipse');"/>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="i_chkFrame_img" name="i_chkFrame_img" onClick="changeClass(0,'btn_wz','btnDown'); btn_click('subMenuBarDiv','btn_wz');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                    </div>
                    <label for="i_chkFrame"> <span class="pad10"> <?php echo $l->m('fr_001'); ?> </span> </label>
                    <input name="i_chkFrame" id="i_chkFrame" type="checkbox" class="chkBox" value="" onClick="setchkBox('i_chkWizard','i_chkBevel','i_chkFrame','i_chkShadow','i_chkBorder','i_chkCorner','i_chkEllipse');" />
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="i_chkShadow_img" name="i_chkShadow_img" onClick="changeClass(0,'btn_wz','btnDown'); btn_click('subMenuBarDiv','btn_wz');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                    </div>
                    <label for="i_chkShadow"> <span class="pad10"> <?php echo $l->m('sh_001'); ?> </span> </label>
                    <input name="i_chkShadow" id="i_chkShadow" type="checkbox" class="chkBox" value="" onClick="setchkBox('i_chkWizard','i_chkBevel','i_chkFrame','i_chkShadow','i_chkBorder','i_chkCorner','i_chkEllipse');"/>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="i_chkBorder_img" name="i_chkBorder_img" onClick="changeClass(0,'btn_wz','btnDown'); btn_click('subMenuBarDiv','btn_wz');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                    </div>
                    <label for="i_chkBorder"> <span class="pad10"> <?php echo $l->m('br_001'); ?> </span> </label>
                    <input name="i_chkBorder" id="i_chkBorder" type="checkbox" class="chkBox" value="" onClick="setchkBox('i_chkWizard','i_chkBevel','i_chkFrame','i_chkShadow','i_chkBorder','i_chkCorner','i_chkEllipse');"/>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="i_chkCorner_img" name="i_chkCorner_img" onClick="changeClass(0,'btn_wz','btnDown'); btn_click('subMenuBarDiv','btn_wz');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                    </div>
                    <label for="i_chkCorner"> <span class="pad10"> <?php echo $l->m('rc_001'); ?> </span> </label>
                    <input name="i_chkCorner" id="i_chkCorner" type="checkbox" class="chkBox" value="" onClick="setchkBox('i_chkWizard','i_chkBevel','i_chkFrame','i_chkShadow','i_chkBorder','i_chkCorner','i_chkEllipse');"/>
                  </div>
                  <div class="rowDiv">
                    <div class="btnRight">
                      <img id="i_chkEllipse_img" name="i_chkEllipse_img" onClick="changeClass(0,'btn_wz','btnDown'); btn_click('subMenuBarDiv','btn_wz');" src="images/infi_off.gif" onMouseOver=" if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa_off.gif') { this.src='images/infa.gif' } else {this.src='images/infi.gif'};" onMouseOut= "if (this.src.substring(this.src.lastIndexOf('/')+1,this.src.length) == 'infa.gif') { this.src='images/infa_off.gif' } else {this.src='images/infi_off.gif'};"width="16" height="16" alt="<?php echo $l->m('se_099'); ?>" title="<?php echo $l->m('se_099'); ?>" />
                    </div>
                    <label for="i_chkEllipse"> <span class="pad10"> <?php echo $l->m('el_001'); ?> </span> </label>
                    <input name="i_chkEllipse" id="i_chkEllipse" type="checkbox" class="chkBox" value="" onClick="setchkBox('i_chkWizard','i_chkBevel','i_chkFrame','i_chkShadow','i_chkBorder','i_chkCorner','i_chkEllipse');" />
                  </div>
                </div>
              </div>
            </div>
            <!- // seDiv -->
          </div>
        </div>
      </div>
      <!- // subMainDivWrap -->
      <!- footer ----------------------------------------------------------- -->
      <div id="ftDivWrap">
        <div id="ftDiv">
          <input type="button" value="<?php echo $l->m('im_005'); ?>" class="btn" onClick="insertImage();" />
          <span class="pad5">
          <input type="button" value="<?php echo $l->m('im_006'); ?>" class="btn" onClick="top.window.close();" />
          </span>
        </div>
      </div>
      <!- // ftDivWrap -->
    </div>
  </div>
  <!- // outerDivWrap -->
</form>
</body>
</html><?php	
	// ============================================================
	// = create library list V 1.0, date: 05/05/2005              =
	// ============================================================
	function liboptions($arr, $prefix = '', $sel = '', $type = '') {
  		global $cfg;
		global $l;
		$retval = '';
				
		if ($type == 'ov') { // called from overlay
			if (isset($cfg['olay']) && $cfg['olay'] != '') {									
				$ovDir = absPath($cfg['olay']);					
				$arr[] = array ( // add overlay image directory to libraries					
					'value'	=> absPath($cfg['olay']),								
					'text' 	=> $l->m('ov_001'),
				);		
			};
		};
		
  		foreach($arr as $lib) {			
    		$retval .= '<option value="' . absPath($lib['value']) . '"' . (($lib['value'] == $sel) ? ' selected="selected"' : '') . '>' . $prefix . $lib['text'] . '</option>' . "\n";
  		}
  		return $retval;
	}	
	// ============================================================
	// = write options values V 1.0, date: 04/27/2005             =
	// = min, max, step, divide, unit, selected, decimals         =
	// ============================================================		
	function writeOptions($min, $max, $s=1, $d=1, $u='', $sel=0) {
   		$retstr = '';
		for($i = $min; $i <= $max; $i += $s){
      		$retstr .= "<option value=\"" . number_format(($i/$d),2);			
      		if ($i == $sel) {
				$retstr .= "\" selected";
			 	$retstr .= ">";
			 } else {
			 	$retstr .= "\">";
			 }			
      			if ($d != 1) {
					$retstr .= number_format(($i/$d),2);
      			} else {
					$retstr .= $i;
				}
				$retstr .= $u;
				$retstr .= "</option>\n";
      		}
   		return $retstr;
   	}
	// ============================================================
	// = get fonts, masks, and watermarks V 1.0, date: 04/22/2005 =
	// ============================================================
	function getItems($dir, $type, $valid) {		
		global $cfg;
		global $l;
		$path = fullPath($dir); // get full path like D:/www/.....     
		if ($handle = @opendir($path)) {
			$retstr = '';
			$files = array();
			$valids = (is_array($valid) ? implode('|', $valid) : $valid);			
			while (($file = readdir($handle)) !== false) {                                                            
				if (is_file($path . $file) && eregi('\.(' . $valids . ')$', $file, $matches)) {                                                                                   
					$files[$path . $file] = $matches[0];
				}
			}
			closedir($handle);                                               
			ksort($files);
			
			$radiocounter = 0;
			foreach ($files as $filename => $ext) {
				$noext = str_replace($ext, '', basename($filename));				
				if ($type == 'wm') { // watermark images
					$src = $cfg['scripts'] . 'phpThumb/phpThumb.php?src=' . $dir . basename($filename) . '&w=32;&h=32&zc=1&f=gif';
					$retstr .= '<div><input type="radio" name="wmf" value="' . $dir . basename($filename) . '" class="chkBox"' . ((++$radiocounter == 1) ? "checked=\"checked\"" : "") . ' /><img src="' . htmlentities($src, ENT_QUOTES) . '" alt="' . htmlentities($noext, ENT_QUOTES) . '" title="' . htmlentities($noext, ENT_QUOTES) . '" align="middle" /></div>' . "\n";
				} else if ($type == 'ms') { // mask images
					$src = $cfg['scripts'] . 'phpThumb/phpThumb.php?src=' . $dir . basename($filename) . '&w=32;&h=32&zc=1&q=85&f=jpeg';
					$retstr .= '<div><input type="radio" name="msf" value="' . $dir . basename($filename) . '" class="chkBox"' . ((++$radiocounter == 1) ? "checked=\"checked\"" : "") . ' /><img src="' . htmlentities($src, ENT_QUOTES) . '" alt="' . htmlentities($noext, ENT_QUOTES) . '" title="' . htmlentities($noext, ENT_QUOTES) . '" width="32" height="32" align="middle" /></div>' . "\n";
				} else if ($type == 'ft') { // true type fonts					
					$retstr .= '<option value="' . basename($filename) . '">' . htmlentities($noext, ENT_QUOTES) . '</option>' . "\n";	
    			};
			}
			return $retstr;
		} else {
			echo $l->m('er_027');
			return false;
		}	
	}
	// ============================================================
	// = create thumb sizes V 1.0, date: 05/23/2005               =
	// ============================================================
	function thumbSizes($arr, $sel = '') {
  		global $l;
		$retval = '';
  		foreach($arr as $key => $thumb) {			
			$retval .= '<div>' . '<input id="chkThumbSize[' . $key . ']" name="chkThumbSize[' . $key . ']" class="chkBox" type="checkbox" value="' . $key . '"' . (($key == 0) ? ' checked="checked"' : '') . ' />' . '<span class="frmText">' . (($thumb['size'] == '*') ? $l->m('in_022') . '&nbsp;'  : $thumb['size'] . ' px' ) . '</span>' . (($thumb['crop'] == true) ? '<img src="images/thbCrop.gif" align="middle" width="10px" height="10px" alt="' . $l->m('in_023') . '" title="' . $l->m('in_023') . '" />' : '') . '</div>' . "\n";
		}
  		return $retval;
	}
	// ============================================================
	// = absolute to full path V 1.0, date: 05/07/2005            =
	// ============================================================
	function fullPath($dir) {				
		global $cfg;
		$path = str_replace('//', '/', $cfg['root_dir'] . $dir);		
		return $path;
	}
	// ============================================================
	// = abs path - add slashes V 1.0, date: 05/10/2005           =
	// ============================================================
	function absPath($path) {		
		if(substr($path,-1)  != '/') $path .= '/';
		if(substr($path,0,1) != '/') $path = '/' . $path;
		return $path;
	}
// ============================================================
// = css styles V 1.0, date: 08/06/2005                       =
// ============================================================
	function getStyles($cap) {
		$styles = '';
		global $cfg;
		foreach ($cfg['style'] as $key => $value) {
			$pos = strrpos($key,'capDiv'); // is caption style
			if ($cap == false && $pos === false) {
					$styles .= '<option value="'. $key . '">' . $value . '</option>';
			} elseif ($cap == true && $pos !== false) {
					$styles .= '<option value="'. $key . '">' . $value . '</option>';
			}
		}
		return $styles;
	}
//-------------------------------------------------------------------------
?>
