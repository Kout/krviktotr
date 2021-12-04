<?php
// ================================================
// PHP image manager - iManager 
// ================================================
// iManager dialog - examples
// ================================================
// Developed: net4visions.com
// Copyright: net4visions.com
// License: LGPL - see license.txt
// (c)2005 All rights reserved.
// ================================================
// Revision: 1.0                   Date: 06/04/2005
// ================================================

	//-------------------------------------------------------------------------
	// include configuration settings
	include '../../config/config.inc.php';
	include '../../langs/lang.class.php';	
	//-------------------------------------------------------------------------
	// language settings	
	if (isset($_REQUEST['lang'])) { 
		$l = new PLUG_Lang(@$_REQUEST['lang']);
	} else {
		$l = new PLUG_Lang($cfg['lang']); // default language (en)
	}
	$l->setBlock('examples');	
?>
<!-- do not delete this line - it's need for proper working of the resizeDialogToContent() function -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $l->m('ex_001'); ?></title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $l->getCharset(); ?>">
<style type="text/css">
<!--
@import url("../../css/style.css");
-->
</style>
<script language="javascript" type="text/javascript" src="../../scripts/resizeDialog.js"></script>
<script language="javascript" type="text/javascript">
<!--
// ============================================================
// = symbols init V 1.0, date: 03/31/2005                     =
// ============================================================
	function init() {		
		var eProps = window.dialogArguments;		
		iManager   = eProps.iManager;
		resizeDialogToContent();
		window.focus();
  	}		
//-->
</script>
</head>
<body onload="init()" dir="<?php echo $l->getDir() ;?>">
<form action="" method="post" enctype="multipart/form-data" name="examples" target="_self" id="examples">
  <div id="dialog">
    <div class="headerDiv">
      <?php echo $l->m('ex_002'); ?>
    </div>
    <div class="brdPad">
      <div class="expDiv">
        <?php			
			$dir =  './';                               
			if ($handle = opendir($dir)) {
			 	$files = array();                                                              
			 	$valids = implode('|', $cfg['valid']);
			 	while (($file = readdir($handle)) !== false) {                                                            
					if (is_file($dir . $file) && eregi('\.(' . $valids . ')$', $file, $matches)) {                                                                                   
						$files[$dir . $file] = $matches[0];
					}
			 	}
			 	closedir($handle);                                               
			 	ksort($files);
			 	foreach ($files as $filename => $ext) {
			  		$noext = str_replace($ext, '', basename($filename));
			  		echo '<div><img src="'.htmlentities($filename, ENT_QUOTES).'"  alt="'.htmlentities($noext, ENT_QUOTES).'" title="'.htmlentities($noext, ENT_QUOTES).'" width="45" height="45" align="middle" /></div>' . "\n";
			 	}
			}
		?>
      </div>
      <!- clear floats ------------------------------------------------------ -->
      <div class="clrFloat">
      </div>
      <div class="ftDiv">
        <input type="button" value="<?php echo $l->m('ex_003'); ?>" class="btn" onclick="window.close();" />
      </div>
    </div>
  </div>
</form>
</body>
</html>
