<?php
/* include "../vklad/stalice.php";   */
?>
<!DOCTYPE HTML>
<!--[if lt IE 7 ]><html class="ie ie6" lang="cs"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="cs"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="cs"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="cs"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<link rel="stylesheet" href="<?php echo CESTA; ?>/sprava/vzhled/bootstrap.css">
<link rel="stylesheet" href="<?php echo CESTA; ?>/sprava/vzhled/vzhled-sprava.css">

<script type="text/javascript" src="<?php echo CESTA; ?>/skrypti/jquery.js"></script>
<script type="text/javascript" src="skrypti/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
tinyMCE.init({
	language : 'cs',
	mode : "textareas",
	theme : "advanced", 
	plugins: "nonbreaking,wordcount,autosave,table,paste",
	paste_text_sticky : true,
	setup : function(ed) {
		ed.onInit.add(function(ed) {
			ed.pasteAsPlainText = true;
		});
	},
	theme_advanced_toolbar_location : "top",
	theme_advanced_toolbar_align : "left",
	theme_advanced_buttons1 : "nonbreaking,charmap,separator,bold,italic,strikethrough,link,separator,bullist,numlist,separator,image,separator,formatselect,separator,undo,redo,separator,code",
	theme_advanced_buttons2 : "",
	theme_advanced_buttons3 : "",
	theme_advanced_blockformats : "p,h2,h3,h4,h5,h6",
	theme_advanced_resizing : true,
	theme_advanced_statusbar_location : "bottom",
	paste_remove_spans :  true,
	paste_remove_styles : true,
	paste_text_linebreaktype : "p",
	// relative_urls : false,
	// convert_urls : false,
//	document_base_url : '../../../../../',
	content_css : "<?php echo CESTA; ?>/sprava/vzhled/vzhled-tiny.css",
//	entity_encoding : "named",
	entities : "8220,ldquo,8222,bdquo,8230,hellip,8211,ndash,8212,mdash,8224,dagger,160,nbsp,38,amp,34,quot,162,cent,8364,euro,163,pound,165,yen,169,copy,174,reg,8482,trade,8240,permil,60,lt,62,gt,8804,le,8805,ge,176,deg,8722,minus"
	});
</script>
<title>Správa obsahu</title>
</head>
	<?php 
		$fluid = "";
		if(isset($_GET["upravit"]) && ($_GET["upravit"] == "fotky")){
			$fluid .= "-fluid";
		}

	 ?>
<body class="container<?php echo $fluid;?>">