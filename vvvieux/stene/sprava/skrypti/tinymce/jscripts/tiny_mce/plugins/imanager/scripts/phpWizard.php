<?php 	
	// ================================================
	// PHP image manager - iManager 
	// ================================================
	// iManager dialog - object method for phpThumb
	// ================================================
	// Developed: net4visions.com
	// Copyright: net4visions.com
	// License: LGPL - see license.txt
	// (c)2005 All rights reserved.
	// ================================================
	// Revision: 1.0                   Date: 06/14/2005
	// ================================================

	//-------------------------------------------------------------------------
	// include configuration settings
	include dirname(__FILE__) . '/../config/config.inc.php';			
	
	// create new phpThumb() object
  	require_once(dirname(__FILE__) . '/phpThumb/phpthumb.class.php');
  	$phpThumb = new phpThumb();

	// set data	
	$phpThumb->setSourceFilename(str_replace('//', '/', $cfg['root_dir'] . $_REQUEST['src']));
	$phpThumb->config_output_format = $_REQUEST['f']; 								// format
	$phpThumb->config_ttf_directory = dirname(__FILE__) . '/../fonts';				// ttf directory
	$phpThumb->q = $_REQUEST['q']; 													// quality
	$phpThumb->config_error_die_on_error    = true;
	$phpThumb->config_cache_disable_warning = true;
	
	// short form to get all the parameters:	
	/*
	$allowedGETparameters = array('w', 'h', 'f', 'q', 'sx', 'sy', 'sw', 'sh', 'zc', 'bg', 'fltr', 'ra', 'ar', 'aoe', 'far', 'iar');
	foreach ($_GET as $key => $value) {
		if (in_array($key, $allowedGETparameters)) {
			$phpThumb->$key = $value;
		}
	}	
	*/
		
	// resize
	$phpThumb->w 	= $_REQUEST['w']; 												// resize - width
	$phpThumb->h 	= $_REQUEST['h']; 												// resize - height
	$phpThumb->iar 	= $_REQUEST['iar']; 											// resize - ignore aspect ratio 
	$phpThumb->zc 	= $_REQUEST['zc']; 												// resize - zoom crop
	$phpThumb->aoe 	= $_REQUEST['aoe']; 											// resize - allow enlargment
	$phpThumb->far 	= $_REQUEST['far']; 											// resize - force aspect ratio
	
	// crop
	$phpThumb->sx 	= $_REQUEST['sx'];												// crop - top
	$phpThumb->sy 	= $_REQUEST['sy'];												// crop - left
	$phpThumb->sw 	= $_REQUEST['sw'];												// crop - width
	$phpThumb->sh 	= $_REQUEST['sh'];												// crop - height
	
	// rotate
	$phpThumb->ra 	= $_REQUEST['ra'];												// rotate - angle
	$phpThumb->ar 	= $_REQUEST['ar'];												// rotate - type
	$phpThumb->bg 	= $_REQUEST['bg'];												// rotate - background-color
	
	// filters
	$phpThumb->fltr = $_REQUEST['fltr'];											// filter parameters	
	
	//-------------------------------------------------------------------------
	// update image	
	$tfile = pathinfo($_REQUEST['src']);
	$clib  = $tfile['dirname'] . '/'; 												// current library
	$nfile = $tfile['basename'];													// file name
    $ext   = strtolower($tfile['extension']);										// current extension
	$path  = str_replace('//', '/', $cfg['root_dir'] . $clib); 						// remove double slash in path	
	$nfile = fixFileName($nfile); 													// check file name	
	
	if (!isset($_REQUEST['nfi'])) {													// if not set, new file will be created, otherwise existing file will be overwritten	
		$nfile = chkFileName($path, $nfile);										// rename file if new filename already exists
	}
	
	//-------------------------------------------------------------------------
	// generate & output
  	if ($phpThumb->GenerateThumbnail()) {
    	if (!$phpThumb->RenderToFile($path . $nfile)) {    
    		echo 'Failed: ' . implode("\n", $phpThumb->debugmessages);
    	}
		@chmod($path . $nfile, 0755) or die('chmod didn\'t work');		
		unset($phpThumb); 															// free up some memory	
  	} else {
    	// do something with debug/error messages
    	echo 'Failed: ' . implode("\n", $phpThumb->debugmessages);
		return false;
  	}
	
	unset($phpThumb); 
	
	//-------------------------------------------------------------------------
	// escape and clean up file name (only lowercase letters, numbers and underscores are allowed) 
	function fixFileName($file) {
		$file = ereg_replace("[^a-z0-9._]", "", str_replace(" ", "_", str_replace("%20", "_", strtolower($file))));
		return $file;
	}
	//-------------------------------------------------------------------------
	// check whether file already exists; rename file if filename already exists
	// keep looping and incrementing _i filenumber until a non-existing filename is found
	function chkFileName($path, $nfile) {
		$tfile = $nfile;
		$i = 1;
		while (file_exists($path . $nfile)) {
			$nfile = ereg_replace('(.*)(\.[a-zA-Z]+)$', '\1_' . sprintf('%02d',$i) . '\2', $tfile);				
			$i++;
		}
		return $nfile;		
	}
?>