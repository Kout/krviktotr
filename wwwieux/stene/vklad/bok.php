<div id="bok">
<?php
 //zatím extra pro každou možnost, tj. řádné podstránky, pro novinky a pro kdy-hrajem. Posléze třeba jen odlišit dotaz a výpis jednotný
 switch($_GET["str"]){
	case "novinky":	
		for($rok = letos();$rok >= 2011;$rok--){
			$od = $rok."-01-01";
			$do = $rok."-12-31";
			$novinky = dibi::query("SELECT rubrika, nazev, datum, kdy
			FROM [novinky]
			WHERE [datum] >= %d", $od, "AND [datum] <= %d",$do,"
			AND [zobr] IS NOT NULL
			AND [struk] IS NULL
			ORDER BY [datum] DESC");
			echo "<h1><a href=\"#rok-" . $rok . "\" title=\"Novinky z roku " . $rok . "\">" . $rok . "</a></h1>\n<ul>\n";
			while ($novinka =  $novinky->fetch()){
				echo "<li class=\"datum\">";
				if(is_null($novinka["kdy"])){
					echo date("j. n. 'y", strtotime($novinka["datum"]));
				}else{
					echo $novinka["kdy"];
				}
				echo "<p class=\"rubrika\">".$novinka['rubrika']."</p>\n";
				$cestaKnudli = cestaKfotkam("novinky",$novinka["nazev"],NULL,$novinka["datum"],$novinka["rubrika"]);				
				$kNudli = "";
				if(file_exists($cestaKnudli."/".mile_url($novinka['nazev']).".jpg")){
					$kNudli .= " data-nudle=\"".$cestaKnudli."\"";
				}
				echo "<p><a href=\"#".mile_url($novinka['nazev'])."\"".$kNudli.">".$novinka['nazev']."</a></p>\n</li>\n";
			}
			echo "</ul>\n";
		}
		break;
		
	case "kdy-hrajeme":
		foreach($ferman as $klic => $predstaveni){
			$Vystoupeni = dibi::query("SELECT st_vystup.cis, st_vystup.nazev AS vystup, st_vystup.kdy, st_vystup.nekdy, st_vystup.priznak, st_saly.nazev AS sal, st_saly.nadnazev, st_saly.mesto, st_inseminace.nazev AS kus
		FROM [st_vystup]
		LEFT JOIN [st_vystup_saly] ON st_vystup_saly.cis_vystup = st_vystup.cis
		LEFT JOIN [st_saly] ON st_vystup_saly.cis_salu = st_saly.cis
		LEFT JOIN [st_vystup_inseminace] ON st_vystup_inseminace.cis_vystup = st_vystup.cis
		LEFT JOIN [st_inseminace] ON st_vystup_inseminace.cis_inseminace = st_inseminace.cis
		WHERE st_vystup.kdy " . $predstaveni["dotaz"] . "
		AND [st_vystup.zobr] IS NOT NULL
		ORDER BY st_vystup.kdy " . $predstaveni["rovnat"]);
			echo "<h1><a href=\"#" . $klic . "\" title=\"kdy hrajeme\">" . $predstaveni["nadpis"] . "</a></h1>\n<ul>\n";
			while ($vystoupeni =  $Vystoupeni->fetch()){
				if(!isset($vystoupeni["vystup"])){
					$nazev = $vystoupeni["kus"];
				}else{
					$nazev = $vystoupeni["vystup"];
				}
				$kdy = substr($vystoupeni["kdy"], 0,10);
				if(!is_null($vystoupeni["priznak"])){
					$priznak = "-".$vystoupeni["priznak"];
				}else{
					$priznak = "";
				}
				echo "<li class=\"datum\">";
				if(is_null($vystoupeni["nekdy"])){
					echo (date("j. n. 'y", (strtotime(substr($vystoupeni["kdy"], 0, 10)))));
				}else{
					echo $vystoupeni["nekdy"];
				}
				$cestaKnudli = cestaKfotkam("kdy-hrajeme",$nazev,NULL,$vystoupeni["kdy"],$vystoupeni["priznak"]);
				$kNudli = "";
				if(file_exists($cestaKnudli."/".mile_url($nazev)."-" . $kdy . $priznak .".jpg")){
					$kNudli .= " data-nudle=\"".$cestaKnudli."\"";
				}
				echo "<p class=\"rubrika\">" . $vystoupeni["mesto"] . "</p><p><a href=\"#".mile_url($nazev)."-" . $kdy . $priznak . "\"".$kNudli.">".$nazev."</a></p>\n";
				//echo $vystoupeni["nazev"] . "</p>\n";
				echo "</li>\n";
			}
			echo "</ul>";
		}
		break;
	case "tvorba":
		foreach($GLOBALS["dilo"] as $klic => $kus){
			$pociny = dibi::query("SELECT *
				FROM [st_inseminace] 
				WHERE ", $kus["where"],
				"AND [zobr] IS NOT NULL
				ORDER BY [st_inseminace.rok] DESC");
			echo "<h1><a href=\"#" . $klic . "\">" . $GLOBALS["dilo"][$klic]["nadpis"] . "</a></h1>\n<ul>";
			while ($pocin =  $pociny->fetch()){
				// if($klic == "hry"){
					$cestaKnudli = cestaKfotkam("tvorba",$pocin["nazev"],NULL,NULL,NULL);
					$kNudli = "";
					if(file_exists($cestaKnudli."/".mile_url($pocin['nazev']).".jpg")){
						$kNudli .= " data-nudle=\"".$cestaKnudli."\"";
					}
					echo "<li><a href=\"#" . mile_url($pocin["nazev"]) . "\"".$kNudli.">" . dopln_nbsp($pocin["nazev"]) . "</a>";
					echo esli_vypis(substr($pocin["rok"], 0, 4),"","<p class=\"rok\">(",")</p>");
					echo "</li>\n";
				/*}elseif($klic == "desky"){
					echo "<li><a href=\"#" . mile_url($pocin["nazev"]) . "\">" . dopln_nbsp($pocin["nazev"]) . "</a>";
					esli_vypis(substr($pocin["rok"], 0, 4),"","<p class=\"rok\">(",")</p>");
					echo "</li>\n";
				}*/
			}
			echo "</ul>\n";
		}
		break;
	case "o-sobe":
		$podnavigace = dibi::query("SELECT cis, title, nadpis, nazev, url
			FROM [st_str]
			WHERE [zobr] = '1'
			AND [poradi] IS NOT NULL
			AND [rodic] = " . $napln["cis"] . "
			ORDER BY [poradi]");
		// echo "<ul>";			
		while($nav = $podnavigace->fetch()){
			if(is_null($nav["title"])){
				if(is_null($nav["nadpis"])){
					$title = $nav["nazev"];
				}else{
					$title = $nav["nadpis"];
				}
			}else{
				$title = $nav["title"];
			}
			echo "\n\t<h1><a href=\"#". $nav["url"]. "\" title=\"".$title."\">" . dopln_nbsp($nav["nazev"]) . "</a></h1>\n";		
			
			unset($dotaz);
			if($nav["cis"] <> 13){
				$dotaz[] = "SELECT * FROM [st_str] WHERE [rodic] = %i ";
				array_push($dotaz, $nav["cis"], " AND [zobr] IS NOT NULL ORDER BY [poradi]");
			}else{				
				$dotaz = "SELECT cis,jmeno,jmenavigace,url FROM [lidi] WHERE [clen] = 'soubor' AND [zobr] IS NOT NULL ORDER BY [poradi]";
			}			
			$podstranky = dibi::query($dotaz);
			if(count($podstranky)>0){
				echo "<ul>\n";
				while ($podstr =  $podstranky->fetch()){
					if($nav["cis"] <> 13){
						// echo $podstr["url"];
						$nazev = $podstr["nazev"];
						$kobr = $url = $podstr["url"];
					}else{
						if(is_null($podstr["jmenavigace"])){
							$nazev = $podstr["jmeno"];
						}else{
							$nazev = $podstr["jmenavigace"];						
						}
						// $kobr = "soubor/".$podstr["url"];
						$kobr = $url = mile_url($nazev);
					}
					$cestaKnudli = cestaKfotkam($str,$kobr,NULL,NULL,NULL);
					$kNudli = "";
					// echo $cestaKnudli."/".$url.".jpg";
					if(file_exists($cestaKnudli."/".$url.".jpg")){
						$kNudli .= " data-nudle=\"".$cestaKnudli."\"";
					}
					echo "<li><a href=\"#" . $url. "\"".$kNudli.">" . dopln_nbsp($nazev) . "</a></li>\n";				
				}
				echo "</ul>\n";
			}
		
			// echo "</li>\n";
		}
		// echo "<li><h1><a href=\"#divadilna\" data-nudle=\"divadilna\">Hráli s&nbsp;námi</a></h1></li>\n";				
		// echo "<li><h1><a href=\"#hoste\" data-nudle=\"hoste\">Hosté</a></h1></li>\n";				
		// echo "<li><h1><a href=\"#diky\" data-nudle=\"diky\">Děkujeme za pomoc</a></h1></li>\n";				
		// echo "</ul>\n";		
		break;
	default:
		$podnavigace = dibi::query("SELECT cis, title, nadpis, nazev, url
			FROM [st_str]
			WHERE [zobr] = '1'
			AND [poradi] IS NOT NULL
			AND [rodic] = " . $napln["cis"] . "
			ORDER BY [poradi]");
		// echo "<ul>";			
		while($nav = $podnavigace->fetch()){
			// echo "\n\t<li>";
			echo "<h1><a href=\"#";
			echo $nav["url"];
			echo "\" title=\"";
			if(is_null($nav["title"])){
				if(is_null($nav["nadpis"])){
					echo $nav["nazev"];
				}else{
					echo $nav["nadpis"];
				}
			}else{
				echo $nav["title"];
			}
			echo "\">" . dopln_nbsp($nav["nazev"]) . "</a></h1>\n";
		}
		// echo "</ul>\n";		
		break;
}
?>
</div>
	