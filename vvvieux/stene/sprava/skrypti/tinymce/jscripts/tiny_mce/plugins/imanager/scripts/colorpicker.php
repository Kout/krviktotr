<?php 
	// ================================================
	// PHP image manager - iManager 
	// ================================================
	// iManager dialog -color picker
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
	include '../config/config.inc.php';	
	include '../langs/lang.class.php';	
	// language settings	
	$l = (isset($_REQUEST['lang']) ? new PLUG_Lang($_REQUEST['lang']) : new PLUG_Lang($cfg['lang']));
	$l->setBlock('colorpicker');

	//-------------------------------------------------------------------------
	// color picker variables
		$rows = 7;
		$cols = 7;
		$cellsize = 10;
		$cellspacing = 1;
		$cellpadding = 0;
		$maxwidth = 300;
		$totalsegments = 8; // one for grayscale, the rest for colors
	// end color picker variables	
	//-------------------------------------------------------------------------
	
	function RGB2hex($r, $g, $b) {
		$RGB2hex  = str_pad(dechex(round($r)), 2, '0', STR_PAD_LEFT);
		$RGB2hex .= str_pad(dechex(round($g)), 2, '0', STR_PAD_LEFT);
		$RGB2hex .= str_pad(dechex(round($b)), 2, '0', STR_PAD_LEFT);
		return $RGB2hex;
	}	
	function CheckMaxWidthBR(&$currentwidth, $maxwidth, $cols, $cellsize, $cellpadding, $cellspacing) {
		$currentwidth += $cols * ($cellsize + ($cellpadding + $cellspacing * 2));
		if ($currentwidth >= $maxwidth) {
			echo '<br clear="all">';
			$currentwidth = 0;
		}
		return true;
	}
?>
<!-- do not delete this line - it's need for proper working of the resizeDialogToContent() function -->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<head>
<title><?php echo $l->m('title'); ?></title>
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $l->getCharset()?>">
<style type="text/css">
<!--
@import url("../css/style.css");
-->
</style>
<script language="javascript" type="text/javascript" src="../scripts/resizeDialog.js"></script>
<script language="javascript" type="text/javascript">
  <!--
	var cur_color; // passed color
  	function init() {
		var args = window.dialogArguments;
		cur_color  = args.curcolor;
		iManager   = args.iManager;
		if (cur_color != null) {
		  document.getElementById('color').value  = cur_color;
		  document.getElementById('ncol').bgColor = cur_color;
		  document.getElementById('ccol').bgColor = cur_color;
		}
		resizeDialogToContent();
  	}
  	function okClick() {		
		window.returnValue = document.getElementById('color').value;
		window.close();		
		if (iManager.isGecko) {			
			<?php
				if (!empty($_REQUEST['callback'])) {
					echo "opener." . $_REQUEST['callback'] . "('" . "',this);\n";
				}
			?>
		}
  	}
  	function cancelClick() {
		window.returnValue = cur_color;
		window.close();
  	}
  	function imgOn(imgid) {			
		document.getElementById('ccol').bgColor = '#' + imgid.id.substring(3);
 	}  	
  	function selColor(colorcode) {
		document.getElementById('ncol').bgColor = '#' + colorcode;
		document.getElementById('ccol').bgColor = '#' + colorcode;
		document.getElementById('color').value  = '#' + colorcode;
  	}
  	function returnColor(colorcode) {
		window.returnValue = '#' + colorcode;
		window.close();
  	}
  	function setColor() {
		document.getElementById('ncol').bgColor = document.getElementById('color').value;
  	}
	//-->
 </script>
</head>
<body onLoad="init()" dir="<?php echo $l->getDir() ;?>">
<div id="dialog">
  <div class="headerDiv">
    <?php echo $l->m('title'); ?>
  </div>
  <div class="brdPad">
    <?php
		$currentwidth = 0;		
		// grayscale
		$graystep = 0xFF / (($rows * $cols) - 1);
		$gray = 0;
		echo '<table border="0" cellspacing="'.$cellspacing.'" cellpadding="'.$cellpadding.'" align="left">'."\n";
			for ($y = 0; $y < $rows; $y++) {
				echo '<tr>'."\n";
				for ($x = 0; $x < $cols; $x++) {
					$hexcolor = RGB2hex($gray, $gray, $gray);
					echo '<td bgcolor="#' . $hexcolor . '"><img id="img' . $hexcolor . '" src="spacer.gif" alt="#' . $hexcolor . '" width="' . $cellsize . '" height="' . $cellsize . '" onMouseOver="imgOn(this)" onClick="selColor(\'' . $hexcolor . '\')" onDblClick="returnColor(\'' . $hexcolor . '\')" style="cursor: pointer;"></td>'."\n";
					$gray += $graystep;
				}
				echo '</tr>'."\n";
			}
		echo '</table>';
		
		CheckMaxWidthBR($currentwidth, $maxwidth, $cols, $cellsize, $cellpadding, $cellspacing);
		
		// NOTE: "round($r) <= 0xFF" is needed instead of just "$r <= 0xFF" because of floating-point rounding errors
		for ($r = 0x00; round($r) <= 0xFF; $r += (0xFF / (($totalsegments - 1) - 1))) {
			echo '<table border="0" cellspacing="' . $cellspacing . '" cellpadding="' . $cellpadding . '" align="left">'."\n";
			for ($g = 0x00; round($g) <= 0xFF; $g += (0xFF / ($rows - 1))) {
				echo '  <tr>'."\n";
				for ($b = 0x00; round($b) <= 0xFF; $b += (0xFF / ($cols - 1))) {
					$hexcolor = RGB2hex($r, $g, $b);
					echo '<td bgcolor="#'.$hexcolor.'"><img id="img'. $hexcolor .'" src="spacer.gif" alt="#' . $hexcolor . '" width="' . $cellsize . '" height="'.$cellsize.'" onMouseOver="imgOn(this)" onClick="selColor(\''.$hexcolor.'\')" onDblClick="returnColor(\''.$hexcolor.'\')" style="cursor: pointer;"></td>'."\n";
				}
				echo '  </tr>'."\n";
			}
			echo '</table>';		
			CheckMaxWidthBR($currentwidth, $maxwidth, $cols, $cellsize, $cellpadding, $cellspacing);
		}
		echo '<br clear="all">';
	?>
    <table width="<?php echo $maxwidth; ?>" border="0" cellpadding="0" cellspacing="0">
      <form name="colorpicker" onSubmit="okClick(); return false;" action="">
        <tr>
          <td id="ccol" align="left" width="30" class="colPrev"><img src="../images/spacer.gif" alt="" width="30" height="30" hspace="0" vspace="0" align="left"></td>
          <td><img src="../images/spacer.gif" alt="" width="10" height="1" hspace="0" vspace="0"></td>
          <td id="ncol" align="left" width="30" class="colPrev"><img src="../images/spacer.gif" alt="" width="30" height="30" hspace="0" vspace="0" align="left"></td>
          <td><img src="../images/spacer.gif" alt="" width="10" height="1" hspace="0" vspace="0"></td>
          <td align="right" valign="bottom" nowrap><input type="text" id="color" name="color" size="7" maxlength="8" class="fldsm" onKeyUp="setColor()">
&nbsp;
            <input type="submit" value="<?php echo $l->m('ok'); ?>" onClick="okClick()" class="btn">
&nbsp;
            <input type="button" value="<?php echo $l->m('cancel'); ?>" onClick="cancelClick()" class="btn">
          </td>
        </tr>
      </form>
    </table>
  </div>
</div>
</body>
</html>