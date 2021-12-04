<?php 
$podobsah= dibi::query("SELECT cis, title, nadpis, nazev, url, obsah, fotky, zvl, vklad
		FROM [st_str]
		WHERE [zobr] = '1'
		AND [poradi] IS NOT NULL
		AND [rodic] = 10
		ORDER BY [poradi]");
if(count($podobsah) > 0){	
	while($obsah = $podobsah->fetch()){
		// echo "\n<div class=\"clanek\" id=\"" . $obsah["url"] ."\">\n<div class=\"nadClankem\">\n
		echo "<section class=\"kotva\" id=\"" . $obsah["url"] ."\">\n<h2>" . $obsah["nadpis"] . "</h2>\n\n";
		echo "\n<div class=\"clanek\">\n<div class=\"text\">\n";
		
		/*echo "\n<div class=\"clanek kotva\" id=\"" . $obsah["url"] . "\">\n<div class=\"nadClankem\">\n
		<h2>" . $obsah["nadpis"] . "</h2>\n</div>\n
		<div class=\"text\">\n";*/
		obsah(NULL,$obsah["obsah"],$obsah["zvl"],$obsah["url"],$obsah["vklad"]);
		// echo "<a name=\"" . $obsah["url"] . "\"></a>\n<div class=\"clanek\">\n<h1>" . $obsah["nadpis"] . "</h1>\n<div class=\"text\">\n";
		 // echo $obsah["obsah"];
		echo "</div>";

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
			echo "</div>\n";							
			echo "<script type=\"text/javascript\">
			$('a.sada-".$obsah["cis"]."').colorbox({current: '{current}. obrázek z {total}', top: '5%', rel: '".$obsah["cis"]."'});\n
			</script>";
		echo "<hr>\n</div>\n";
		}
		echo "</section>\n";
	}
}

?>