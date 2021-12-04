<?php
global $ferman;
foreach($GLOBALS["clenstvi"] as $jak => $clen){
	$dotaz = "SELECT cis,jmeno,jmenavigace,url,bio,oddo,fotky FROM [lidi] WHERE [clen] = '".$jak."' AND [zobr] IS NOT NULL ORDER BY [poradi]";
	$vypis = dibi::query($dotaz);

	echo "<section class=\"kotva\" id=\"" . $jak . "\">\n";
	echo esli_vypis($GLOBALS["clenstvi"][$jak],"h1");
	while ($vypsat =  $vypis->fetch()){
		if(is_null($vypsat["jmenavigace"])){
			$nazev = $vypsat["jmeno"];
		}else{
			$nazev = $vypsat["jmenavigace"];						
		}
		echo "\n<div class=\"clanek\">\n<div class=\"nadClankem\"><a name=\"" . mile_url($nazev) ."\"></a>\n";
		echo esli_vypis($vypsat["jmeno"],"h2");
		echo "</div>";
		echo "<div class=\"text\">\n";
		echo esli_vypis($vypsat["oddo"],"p");
		echo esli_vypis($vypsat["bio"],"");
		echo "</div>";
		if(!is_null($vypsat["fotky"])){
		$fotky = dibi::query("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr
				FROM [st_foto_lidi]
				LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_lidi.cis_fotky
				WHERE st_foto_lidi.cis_lidi= %i", $vypsat["cis"],
					" ORDER BY st_fotky.soubor");
			echo "<div class=\"pfota\">\n";
			// cestaKfotkam($sekce,$nazev,$nahradni= NULL,$kdy,$priznak)
			$cestaKobr = cestaKfotkam("o-sobe",$vypsat["url"],NULL, NULL)."/pidi/";			
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
				echo "<a href=\"".$cesta."\" title=\"".$popisek."\" class=\"sada-" . $vypsat["cis"] . "";
				echo (is_null($fotka["zobr"]) ? " skryt" : "");
				echo "\"><img src=\"".CESTA."/".$cestaKobr.$fotka["soubor"].".jpg\" ".$foto[3]." alt=\"" . strip_tags($fotka["popisek"])  . "\"></a>\n";
			}
			echo "</div>\n";
			echo "<script type=\"text/javascript\">
			$('a.sada-".$vypsat["cis"]."').colorbox({current: '{current}. obrázek z {total}', top: '5%', rel: '".$vypsat["cis"]."'});\n
			</script>";
		}
		echo "<hr>\n</div>\n";
	}
	echo "</section>\n";
}
echo "<script type=\"text/javascript\">
$('.pfota a').colorbox({current: '{current}. obrázek z {total}', top: '5%'});\n
</script>";
?>
