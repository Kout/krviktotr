<!DOCTYPE HTML>
<!--[if lt IE 7 ]><html class="ie ie6" lang="cs"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="cs"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="cs"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="cs"> <!--<![endif]-->
<html lang="cs">
<head>
<meta charset="utf-8">
<link rel="stylesheet/less" href="vzhled/vzhled-gal.less">
<link rel="stylesheet" href="vzhled/colorbox.css">
<script src="skrypti/less.js"></script>
<script src="skrypti/jquery.js"></script>
<script src="skrypti/colorbox.js"></script>
<title></title>
</head>
<body>
<h1>Svázaná v Újezdě 21. ledna 12 <small>(fotil Vladimír Kyzlink)</small></h1>
<?php
function pre($pole){
	echo "<pre>";
	print_r($pole);
	echo "</pre>";
}
function naber_obr($obrazarna, $obrAlt = NULL){
    $obraz = "obr/" . $obrazarna. "/";
	$obraz .= "pidi/";
	$fotky = glob($obraz."*.jpg");
	if(!is_null($obrAlt)){
			$fotky["alt"] =  $obrAlt . " - náhled";
		}else{
			$fotky["alt"] = "náhled obrázku ".$obrUrl."ze složky ".$obrazarna;
		}
	return $fotky;
}
$fotky = naber_obr("oujezd","Svázaná v Újezdě 21. ledna 12");
foreach($fotky as $fotka){
	if(file_exists($fotka)){
		$rozmery = getimagesize($fotka);
		echo "<div class=\"fotka\">\n<a href=\"";
		echo (str_replace("pidi", "obr", $fotka));
		echo "\" title=\" \" rel=\"nahledy\" class=\"colorbox\"><img src=\"" . $fotka . "\" ";
		echo $rozmery[3];
		echo " alt=\"\"></a>\n";
		echo "</div>\n";
	}
}
 echo "<hr>";
?>
<script type="text/javascript">
$('.colorbox').colorbox({top: 10, current: '{current}. obrázek z {total}',title:false});
</script>
</body>
</html>