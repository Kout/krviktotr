<?php
/*$dilo = array(
	"hry" => array(
		"from" => "st_inseminace",
		"nadpis" => "Na repertoáru",
	),
	"desky" => array(
		"from" => "st_desky",
		"nadpis" => "Desky",
	),
);*/
foreach($GLOBALS["dilo"] as $klic => $kus){
	$pociny = dibi::query("SELECT *
		FROM [st_inseminace] 
		WHERE ", $kus["where"],
		" ORDER BY [st_inseminace.rok] DESC
		");
	echo "<section class=\"kotva\" id=\"" . $klic ."\">\n<h1>" . $GLOBALS["dilo"][$klic]["nadpis"] . "</h1>\n";
	// echo "\n<div class=\"clanek\">\n<div class=\"text\">\n";
	// echo "<a name=\"" . $klic . "\"></a>
	// <h1>" . $GLOBALS["dilo"][$klic]["nadpis"] . "</h1><br>\n";//<div id=\"" . $klic . "\">\n
	while ($pocin =  $pociny->fetch()){
		// if($klic == "hry"){
		echo "<section class=\"kotva\" id=\"" . mile_url($pocin["nazev"]) . "\">\n";
		echo esli_vypis($pocin["nazev"],"h2");
		echo esli_vypis($pocin["podtitul"],"h3");
		echo "<div class=\"clanek\">\n<div class=\"text\">\n";
		echo esli_vypis($pocin["autori"],"p");
		echo esli_vypis($pocin["anotace"],"");
		$vystoupeni = dibi::query("SELECT st_vystup.cis AS cislo, st_vystup.nazev AS vystup, st_vystup.kdy, st_vystup.nekdy, st_vystup.priznak, st_saly.nazev AS sal, st_saly.nadnazev, st_saly.mesto, st_inseminace.cis, st_inseminace.nazev AS kus, st_inseminace.podtitul, st_inseminace.co, st_inseminace.plakat
		FROM [st_vystup]
		LEFT JOIN [st_vystup_saly] ON st_vystup_saly.cis_vystup = st_vystup.cis
		LEFT JOIN [st_saly] ON st_vystup_saly.cis_salu = st_saly.cis
		LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
		LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
		WHERE st_vystup.kdy > NOW()
		AND [st_vystup.zobr] IS NOT NULL
		AND [st_inseminace.cis] = %i",$pocin["cis"],
		"ORDER BY [st_vystup.kdy] ASC");
				if(count($vystoupeni) > 0){
					echo "<h4 class=\"block\">Nejbližší termíny</h4>\n<ul>";
					while($priste = $vystoupeni->fetch()){
						if(!isset($priste["vystup"])){
							$nazev = $priste["kus"];
						}else{
							$nazev = $priste["vystup"];
						}
						$kdy = substr($priste["kdy"], 0,10);
						if(!is_null($priste["priznak"])){
							$priznak = "-".$priste["priznak"];
						}else{
							$priznak = "";
						}
						if(is_null($priste["nekdy"])){
							echo esli_vypis(date("j. n. 'y", (strtotime(substr($priste["kdy"], 0, 10)))),"","<li><a href=\"kdy-hrajeme#".mile_url($nazev)."-" . $kdy . $priznak . "\">"," ");
						}else{
							echo esli_vypis($priste["nekdy"],"","<li><a href=\"kdy-hrajeme#".mile_url($nazev)."-" . $kdy . $priznak . "\">");
						}
						echo esli_vypis($priste["mesto"],"","&nbsp;(",",&nbsp;");
						echo esli_vypis($priste["sal"],"","",")</a></li>\n");
					}
					echo "</ul>";
				}
				if(!is_null($pocin["premca"])){				
					echo esli_vypis($pocin["premca"],"","<h4>Premiéra </h4><p>","</p>");
				}else{
					premDerniery($pocin["cis"],"premiera","Premiéra");
				}
				if(!is_null($pocin["naposledy"])){				
					echo esli_vypis($pocin["naposledy"],"","<h4>Naposledy uvedeno </h4><p>","</p>");
				}elseif(!is_null($pocin["napos"])){				
					premDerniery($pocin["napos"],"naposledy","Naposledy uvedeno");
				}
				if(!is_null($pocin["derniera"])){			
					echo esli_vypis($pocin["derniera"],"","<h4>Derniéra </h4><p>","</p>");
				}elseif(!is_null($pocin["dern"])){			
					premDerniery($pocin["dern"],"derinera","Derniéra");
				}
				echo esli_vypis($pocin["popis"],"");
				//echo "přidat výpis představení minulých (pouze datum, místo) a odkazovat na stránku kdy hrajeme na danou záložku)";
				echo "</div>\n";
				if(!is_null($pocin["plakat"])){
				$fotky = dibi::query("SELECT st_fotky.cis, st_fotky.soubor, st_fotky.popisek, st_fotky.autor, st_fotky.kdy, st_fotky.nekdy, st_fotky.zobr
					FROM [st_foto_inseminace]
					LEFT JOIN [st_fotky] ON st_fotky.cis = st_foto_inseminace.cis_fotky
					WHERE st_foto_inseminace.cis_inseminace = %i", $pocin["cis"],
					" ORDER BY st_fotky.soubor");
				echo "<div class=\"pfota\">\n";
				//cestaKfotkam($sekce,$nazev,$nahradni= NULL,$kdy,$priznak)
				$cestaKobr = cestaKfotkam("tvorba",$pocin["nazev"],NULL,NULL,NULL)."/pidi/";
//echo $cestaKobr;
				while($fotka = $fotky->fetch()){
					$foto = getimagesize($cestaKobr.$fotka["soubor"].".jpg");
					$cesta = CESTA."/".str_replace("/pidi/", "/obr/", $cestaKobr) . $fotka["soubor"]. ".jpg";
					$popisek = "";
					if(is_null($fotka["nekdy"])){
						$popisek .= $fotka["kdy"] ? datum(strtotime($fotka["kdy"])):"";
					}else{
						$popisek .= $fotka["nekdy"];
					}			
					$popisek .= $fotka["popisek"] ?  " <span class=\"rude\">|</span> " . $fotka["popisek"]:"";
					$popisek .= $fotka["autor"] ?  " <span class=\"rude\">|</span> " . $fotka["autor"]:"";	
					$altitle = prosty_text($popisek);
					echo "<a href=\"".$cesta."\" title=\"".$altitle."\" class=\"sada-" . $pocin["cis"] . "";
					echo (is_null($fotka["zobr"]) ? " skryt" : "");
					echo "\">";
					if($popisek <> ""){
						echo "<div class=\"skryt\">".$popisek."</div>";					
					}
					echo "<img src=\"".CESTA."/".$cestaKobr.$fotka["soubor"].".jpg\" ".$foto[3]." alt=\"" . $altitle  . "\"></a>\n";
				}			
				echo "<script type=\"text/javascript\">\n$('a.sada-".$pocin["cis"]."').colorbox({current: '{current}. obrázek z {total}', top: '5%', rel: '".$pocin["cis"]."'});\n</script>\n";
				echo "<hr>\n";
				echo "</div>\n";
			}
		// }
		/*elseif($klic == "desky"){
			echo "<div class=\"clanek\">\n";
			echo "<a name=\"" . mile_url($pocin["nazev"]) . "\"></a>";
			echo "<h2>" . dopln_nbsp($pocin["nazev"]) . " (" . $pocin["co"] . ")</h2>";
			echo esli_vypis($pocin["podtitul"],"h3");
			echo esli_vypis($pocin["autori"],"p");
			echo esli_vypis($pocin["anotace"],"p");
			if(!is_null($pocin["rok"])){
				echo esli_vypis(datum(strtotime($pocin["rok"])),"","<h4>Vydáno </h4><p>","</p>");
			}
			echo esli_vypis($pocin["sleeve"],"p");
		}*/
		echo "<hr></div>\n</section>\n";
	}
	echo "</section>\n";
}
echo "<script type=\"text/javascript\">
$('.pfota a').colorbox({current: '{current}. obrázek z {total}', top: '10%', title: function(){
	var titel = $(this).children('.skryt').html();
	return titel;
}});\n
</script>";
?>
