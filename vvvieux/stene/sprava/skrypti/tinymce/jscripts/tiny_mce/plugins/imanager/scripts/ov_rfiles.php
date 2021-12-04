<?php
	// ================================================
	// PHP image manager - iManager 
	// ================================================
	// iManager dialog - file functions
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: LGPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.0                   Date: 05/24/2005
	// ================================================
	
	//-------------------------------------------------------------------------
	// include configuration settings
	include dirname(__FILE__) . '/../config/config.inc.php';
	include dirname(__FILE__) . '/../langs/lang.class.php';	
	//-------------------------------------------------------------------------	
	// language settings	
	$l = (isset($_REQUEST['lang']) ? new PLUG_Lang($_REQUEST['lang']) : new PLUG_Lang($cfg['lang']));
	$l->setBlock('iManager');
	//-------------------------------------------------------------------------	
	// set variables	
	// parameters
	$param  = (isset($_REQUEST['param']) ? $_REQUEST['param'] : '');	
	if (isset($param)) {
		$param  = explode('|',$param);
	}	
	// set action
	$action = (isset($_REQUEST['action']) ? $_REQUEST['action'] : '');		
	// set image library	
	$clib = (isset($_REQUEST['clib']) ? $_REQUEST['clib'] : (isset($_REQUEST['ilibs']) ? $_REQUEST['ilibs'] : $cfg['ilibs'][0]['value']));	
	$cfile = (!isset($cfile) ? '' : $cfile);
	//-------------------------------------------------------------------------
	// file/directory actions	
	if ($param[0] == 'update') {// action: update image list and select current image			
		$action = $param[0];
		$cfile  = $param[1];	// current filename					
	}
?>
<!-- do not delete this line - it's need for proper working of the resizeDialogToContent() function -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $l->m('im_002'); ?></title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $l->getCharset(); ?>">
<style type="text/css">
<!--
@import url("../css/style.css");

html, body {
	margin: 0px;
	padding: 0px;
}
form {
	width: auto;		
}
-->
</style>
<script language="JavaScript" type="text/JavaScript">
<!--
// ============================================================
// = image div V 1.0, date: 05/05/2005                        =
// ============================================================	
	// div hover
	function ovDiv_over() {
		if (this.className != 'ovBtnDown') {
			this.className  = 'ovBtnOver';
		}
	}
	// div out
	function ovDiv_out() {
		if (this.className != 'ovBtnDown') {
			this.className  = 'ovBtnUp';
		}
	}
	// div down
	function ovDiv_down() {
		if (this.className != 'ovBtnDown') {
			this.className  = 'ovBtnDown';
		}
	}
	// div click
	function ovDiv_click() {		
		x = document.getElementById('ovSelDiv').getElementsByTagName('div');
		for (var i = 0; i < x.length; i++) {
			if (x[i].className == 'ovBtnDown') {
				if (x[i] != this) {
					x[i].className = 'ovBtnUp';
				}
			}
		}		
		imageChangeClick(this);		
	}
// ============================================================
// = init filelist - set attributes V 1.0, date: 04/18/2005   =
// ============================================================
	function init() {		
		var formObj = document.forms[0];
		// init mouse events on div tags <div>
		var x = document.getElementById('ovSelDiv').getElementsByTagName('div');
		for (var i = 0; i < x.length; i++) {
			if (x[i].className == 'ovBtnUp') {
				x[i].onmouseover = ovDiv_over;
				x[i].onmouseout  = ovDiv_out;
				x[i].onmousedown = ovDiv_down;
				x[i].onclick     = ovDiv_click;
			}
		}	
		
		// actions
		var action = formObj.action.value;		
		if (action == 'update') {			
			var tfile = '<?php echo $cfile; ?>';			
			getObject(tfile);		
		}
	}
// ============================================================
// = image change - set attributes V 1.0, date: 04/18/2005    =
// ============================================================	
	function imageChangeClick(obj) {
		var formObj = document.forms[0];
		var action  = formObj.action.value;
		if (obj) {			
			parent.document.getElementById('ovFile').value = obj.attributes['ifile'].value;
			parent.document.getElementById('chkOverlay').checked = true;				
		}		
		formObj.action.value = null; 		// resetting action value to null;					
	}
// ============================================================
// = get current file - set attrib V 1.0, date: 04/18/2005    =
// ============================================================		
	function getObject(tfile) {		
		var x = document.getElementById('ovSelDiv').getElementsByTagName('div');
		for (var i = 0; i < x.length; i++) {
			if (x[i].attributes['ifile'].value == tfile) {					
				x[i].className = 'ovBtnDown';				
				imageChangeClick(x[i]);				
			}
		}
	}
// ============================================================
// = load/hide message, date: 02/08/2005                      =
// ============================================================
	function hideloadmessage() {
		document.getElementById('dialogLoadMessage').style.display = 'none'
	}
-->
</script>
<title>Image list</title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $l->getCharset(); ?>">
</head>
<body onload="init(); hideloadmessage();" dir="<?php echo $l->getDir(); ?>">
<?php include 'loadmsg.php'; ?>
<form id="rfiles" name="rfiles" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" target="_self">
  <input type="hidden" name="lang" value="<?php echo $l->lang; ?>" />
  <input type="hidden" id= "action" name="action" value="<?php echo $action; ?>" />
  <div id="ovSelDiv">    
	<?= getItems($cfg['root_dir'] . $clib, $cfg['valid']); ?>
  </div>
</form>
</body>
</html>
<?php
	// get images
	function getItems($dir, $valid) {			
		global $clib; // current library 			
		global $cfg;
		global $l;		
		$retstr = ''; 			                           
		if ($handle = opendir($dir)) {			
			$files = array();
			$valids = implode('|', $valid);			
			while (($file = readdir($handle)) !== false) {                                                            
				if (is_file($dir . $file) && eregi('\.(' . $valids . ')$', $file, $matches)) {                                                                                   
					$files[$dir . $file] = $matches[0];
				}
			}
			closedir($handle);                                               
			ksort($files);
			$path  = str_replace('//', '/', $cfg['root_dir'] . $clib); // remove double slash in path				
			foreach ($files as $filename => $ext) {										
				$size     = getimagesize($path . basename($filename));				
				$src      = 'phpThumb/phpThumb.php?src=' . absPath(str_replace($cfg['root_dir'],'', $path)) . basename($filename) . '&w=32&h=32&zc=1'; 				
				$retstr  .= '<div class="ovBtnUp" ifile="' . absPath(str_replace($cfg['root_dir'] ,'', $path)) . basename($filename) . '" >' . '<img src="' . $src . '" width="32" height="32" alt="' . basename($filename) . '; ' . htmlentities($size[0], ENT_QUOTES) . ' x ' . htmlentities($size[1], ENT_QUOTES) . 'px;' . '" title="' . basename($filename) . '; ' . htmlentities($size[0], ENT_QUOTES) . ' x ' . htmlentities($size[1], ENT_QUOTES) . 'px;' . '"/>' . '</div>' .  "\n";
			}			
			return $retstr;
		}
		echo $l->m('er_044');		
		return false;
	}
	// ============================================================
	// = abs path - add slashes V 1.0, date: 05/10/2005           =
	// ============================================================
	function absPath($path) {		
		if(substr($path,-1)  != '/') $path .= '/';
		if(substr($path,0,1) != '/') $path = '/' . $path;
		return $path;
	}
?>