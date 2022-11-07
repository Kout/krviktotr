<?php
include_once "vklad/fce.php";
include_once "vklad/dibi.php";
include_once "vklad/spoj.php";

if(!isset($_GET["str"])){
	$str = "";
}else{
	$str = $_GET["str"];
}

$napln = dibi::fetch("SELECT cis, title, nadpis, nazev, url, obsah, zvl, vklad, rodic
	FROM [st_str]
	WHERE url = %s", $str);
?>
<!DOCTYPE HTML>
<!--[if lt IE 7 ]><html class="ie ie6" lang="cs"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="cs"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="cs"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html lang="cs"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<link rel="shortcut icon" type="image/x-icon" href="http://krviktotr.cz/stene/favicon.ico">
<link rel="stylesheet" href="<?php echo CESTA; ?>/vzhled/vzhled-stene.css">
<!-- <link rel="stylesheet/less" href="<?php echo CESTA; ?>/vzhled/vzhled-stene.less">  
<script src="<?php echo CESTA; ?>/skrypti/less.js"></script> -->
<script src="<?php echo CESTA; ?>/skrypti/jquery.js"></script>
<script src="<?php echo CESTA; ?>/skrypti/colorbox.js"></script>
<script src="<?php echo CESTA; ?>/skrypti/KTuery.js"></script>
<title>Krvik Totr:  <?php 
if (isset($napln["title"])) {
	echo $napln["title"];
}else{
	echo $napln["nadpis"];
}	
?></title>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-32569330-1']);
  _gaq.push(['_setDomainName', 'krviktotr.cz']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</head>
<body style="background-image: url('vzhled/obr/<?php echo $str; ?>.jpg');">
<div id="str">
<?php
if(count($napln) > 0){	
	echo "<div class=\"stat\" id=\"";
	echo ($str == "" ? "doma" : $str);
	echo "\"><a href=\"#nav\" title=\"přeskočit na navigaci\" class=\"skryt\">přeskočit na navigaci</a>\n";
	// echo esli_vypis($napln["nadpis"],"h1");

	obsah($napln["nadpis"],$napln["obsah"],$napln["zvl"],$napln["url"],$napln["vklad"]);

	/* ------------- vložení případného speciálního souboru, který načítá z DtB -----------------  */
	/*if($napln["zvl"] == 'pred'){ //Vkládá se před obsah z DtB
		zvl($napln["url"],$napln["vklad"]);
	}
	
	echo esli_vypis($napln["obsah"],"");
	*/
	$podobsah= dibi::query("SELECT cis, title, nadpis, nazev, url, obsah, fotky, zvl, vklad
		FROM [st_str]
		WHERE [zobr] = '1'
		AND [poradi] IS NOT NULL
		AND [rodic] = " . $napln["cis"] . "
		ORDER BY [poradi]");
	// dibi::dump();
	if(count($podobsah) > 0){	
		while($obsah = $podobsah->fetch()){
			if(!is_null($obsah["nadpis"])){
				echo "<section class=\"kotva\" id=\"" . $obsah["url"] ."\">\n<h1>" . $obsah["nadpis"] . "</h1>\n\n";
				echo "\n<div class=\"clanek\">\n<div class=\"text\">\n";
			}
			obsah(NULL,$obsah["obsah"],$obsah["zvl"],$obsah["url"],$obsah["vklad"]);
			// echo "<a name=\"" . $obsah["url"] . "\"></a>\n<div class=\"clanek\">\n<h1>" . $obsah["nadpis"] . "</h1>\n<div class=\"text\">\n";
			 // echo $obsah["obsah"];
			echo (!is_null($obsah["nadpis"]) ? "</div>":"");

			if(!is_null($obsah["fotky"])){
				$fotky = dibi::query("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr
					FROM [st_foto_str]
					LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_str.cis_fotky
					WHERE st_foto_str.cis_str= %i", $obsah["cis"]);
				echo "<div class=\"pfota\">\n";
				//cestaKfotkam($sekce,$nazev,$nahradni= NULL,$kdy,$priznak)
				$cestaKobr = "obr/str/".$obsah["url"]."/pidi/";
				while($fotka = $fotky->fetch()){
					$foto = getimagesize($cestaKobr.$fotka["soubor"].".jpg");
					$cesta = CESTA."/".str_replace("/pidi/", "/obr/", $cestaKobr) . $fotka["soubor"]. ".jpg";
					$popisek = $fotka["popisek"] ?  " | " . $fotka["popisek"]:"";
					$popisek .= $fotka["autor"] ?  " | " . $fotka["autor"]:"";
					if(is_null($fotka["nekdy"])){
						$popisek .= $fotka["kdy"] ? datum(strtotime($fotka["kdy"])):"";
					}else{
						$popisek .= $fotka["nekdy"];
					}
					echo "<a href=\"".$cesta."\" title=\"".$popisek."\" class=\"sada-" . $obsah["cis"] . "";
					echo (is_null($fotka["zobr"]) ? " skryt" : "");
					echo "\"><img src=\"".CESTA."/".$cestaKobr.$fotka["soubor"].".jpg\" ".$foto[3]." alt=\"" . strip_tags($fotka["popisek"])  . "\"></a>\n";
				}
				echo "</div>\n<hr>\n";
				echo "<script type=\"text/javascript\">
				$('a.sada-".$obsah["cis"]."').colorbox({current: '{current}. obrázek z {total}', top: '5%', rel: '".$obsah["cis"]."'});\n
				</script>";
			}				
			echo (!is_null($obsah["nadpis"]) ? "<hr>\n</div>\n</section>\n":"");			
		}
	}
	
	/*
	if($napln["zvl"] == 'za'){ //Vkládá se před obsah z DtB
		zvl($napln["url"],$napln["vklad"]);
	}*/
//pre($_GET);
//dibi::dump(); 	
//echo dibi::$sql;
	echo "<hr>\n<p id=\"pata\">
&copy; 2012 Krvik Totr Limity / Prostores o. s. | code <a href=\"http://vosk-praha.cz\" title=\"VosK Praha, pomůžeme vám, abyste byli vidět\" target=\"_blank\">VosK Praha</a> | akt. 24. 11. 2012</p>
</div>\n"; /*----- konec náplně ----------*/
}
?>

	<div id="hlava">
			<?php
			// boční nudle: dekorativní fotka ke každému článku, není-li, zobrazí se výchozí nudle k dané sekci
			if($str <> ""){
				$kobr = "obr/".$str."/".$napln["url"].".jpg";
			}else{
				$kobr = "obr/hp/nudle.jpg";
			}
			if(file_exists($kobr)){
				$obr = getimagesize($kobr);
				echo "<img src=\"". $kobr. "\" ".$obr[3]." alt=\"\">\n";
			}
			include_once("vklad/nav.php");
			?>
			

	</div>
<?php
if($str != ""){
	include_once("vklad/bok.php");
}
?>	
<script src="<?php echo CESTA; ?>/skrypti/KTuery.js"></script>	
</div>
</body>
</html>
<?php
if (isset($spoj)){
	mysql_close($spoj);
}
?>