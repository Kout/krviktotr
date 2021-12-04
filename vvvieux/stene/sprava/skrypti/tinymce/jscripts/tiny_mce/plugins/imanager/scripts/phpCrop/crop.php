<?php
// ================================================
// PHP image manager - iManager 
// ================================================
// iManager - crop dialog
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: LGPL - see license.txt
// (c)2005 All rights reserved.
// ================================================
// Revision: 1.0                   Date: 2005/05/01
// ================================================

// parameters ==============
// 	s = source image
// 	w = crop width
// 	h = crop height
//  r = ratio (scalable, resizeable)
// ==========================

	// set variables
	$s     	= $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['s']; // source	
	$mw    	= $_REQUEST['w']; // width
	$mh    	= $_REQUEST['h']; // height
	$mx    	= $_REQUEST['x']; // width
	$my    	= $_REQUEST['y']; // height
	$r     	= $_REQUEST['r']; // ratio (scalable, resizeable)
	$cr_f 	= 1; // used for resizing to fit crop preview ( default = 1 )	
	$d		= $_REQUEST['d'] . '/'; // temp destination path	
	// resize image to max width of crop window
	if( isset($_REQUEST['s']) ) {
		//delete previous temp files		
		$matches = glob($d . '{*.jpg,*.JPG}', GLOB_BRACE);		
		if ( is_array ( $matches ) ) {
   			foreach ( $matches as $fn) {
				@unlink($fn);	
   			}
		}
		
		$cr_mw = 400;
		$cr_mh = 300;		
		$d = $_REQUEST['d'] . '/' . md5( $s . date( "U" ) ) . substr( $s, strlen( $s ) - 4, 4); // create temporary file			
		$sizes = @getimagesize($s);
		
		if ($sizes[0] > $cr_mw || $sizes[1] > $cr_mh) {
			if ( $sizes[0] > $cr_mw ) {
				$cr_f = $sizes[0] / $cr_mw;
			} else {
				$cr_f = $sizes[1] / $cr_mh;
			}
			
			$mw = $mw/$cr_f;
			$mh = $mh/$cr_f;		
		
			include (dirname(__FILE__) . '/../phpThumb/phpthumb.class.php'); 
			$phpThumb = new phpThumb();  					
			$phpThumb->src = $s;					
			if ($sizes[0] < $sizes[1]) { // portrait
				$phpThumb->h = $cr_mh;
			} else { // landscape
				$phpThumb->w = $cr_mw;
			}  
											
  			$phpThumb->config_output_format = 'jpg';  					 	
  				if ($phpThumb->GenerateThumbnail()) {
    				$phpThumb->RenderToFile($d);
					$s = addslashes($d);						
  				} else {
    				// do something with debug/error messages
    				echo 'error_uploading';
					return false;
  				}
			}	
		}
		
	if ( $r == 1 ) {
		$r = 'SCALABLE';
	} else {
		$r = 'RESIZABLE';
	}
	error_reporting (E_ALL ^ E_NOTICE); 
	require dirname(__FILE__) . '/class.cropinterface.php'; 

    $ci = new cropInterface();    
    if (isset($_REQUEST['file'])) {
        $ci->loadImage($_REQUEST['file']);
        $ci->cropToDimensions($_REQUEST['sx'], $_REQUEST['sy'], $_REQUEST['ex'], $_REQUEST['ey']);
        header('Content-type: image/jpeg');
        $ci->showImage('jpg', 85);      	
		exit;
    }
?>
<html>
<head>
<meta http-equiv="imagetoolbar" content="no" />   
</head>
<body>
    <?php
		if ($mw > 0 || $mh > 0) { // if width and height of cropping area are set
    		$ci->setCropDefaultSize($mw,$mh);
		};		
		$ci->setCropStartPosition($mx,$my);	
		$ci->setParam($r); 
		$ci->loadInterface($s,$cr_f); // source, crop factor		
		$ci->loadJavaScript();		 
    ?>
</body>
</html> 